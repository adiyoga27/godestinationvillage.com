<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocationSearchController extends Controller
{
    /**
     * Cari lokasi (provinsi, kab/kota, kecamatan, desa) via API Mengantar.
     */
    public function search(Request $request)
    {
        $q = self::normalizeKeyword((string) $request->query('q', ''));

        if (mb_strlen($q) < 3) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $apiKey = (string) config('services.mengantar.api_key');
        if ($apiKey === '') {
            Log::warning('Location search tanpa MENGANTAR_API_KEY.');

            return response()->json(['success' => true, 'data' => []]);
        }

        $data = Cache::remember('mengantar_location_v2_'.md5(mb_strtolower($q)), now()->addDay(), function () use ($q, $apiKey) {
            $baseUrl = rtrim((string) config('services.mengantar.base_url'), '/');

            try {
                $response = Http::timeout(8)
                    ->acceptJson()
                    ->get($baseUrl.'/api/public/'.$apiKey.'/address/search', ['keyword' => $q]);
            } catch (\Throwable $e) {
                Log::warning('Location search gagal dijangkau: '.$e->getMessage());

                return null;
            }

            if (! $response->successful()) {
                Log::warning('Location search upstream error', ['status' => $response->status(), 'q' => $q]);

                return null;
            }

            return collect($response->json('data') ?? [])
                ->map(fn ($item) => [
                    'province' => $item['PROVINCE_NAME'] ?? null,
                    'city' => $item['CITY_NAME_SI'] ?? $item['CITY_NAME'] ?? null,
                    'district' => $item['DISTRICT_NAME'] ?? null,
                    'subdistrict' => $item['SUBDISTRICT_NAME'] ?? null,
                    'postal_code' => $item['ZIP_CODE'] ?? null,
                ])
                ->filter(fn ($item) => $item['province'] && $item['city'])
                ->values()
                ->all();
        });

        if ($data === null) {
            Cache::forget('mengantar_location_v2_'.md5(mb_strtolower($q)));
            $data = [];
        }

        return response()->json(['success' => true, 'data' => self::rank($data, $q)]);
    }

    /**
     * Buang awalan administratif (kab., kota, kec., desa, …) — API mengembalikan 0 hasil bila ikut dikirim.
     */
    public static function normalizeKeyword(string $q): string
    {
        $q = preg_replace('/\b(kabupaten|kab|kota|kecamatan|kec|kelurahan|kel|desa|provinsi|prov)\b\.?/iu', ' ', $q);

        return trim(preg_replace('/\s+/', ' ', $q));
    }

    /**
     * API mengurutkan hasil per kelurahan, sehingga "karangasem" menampilkan desa bernama
     * Karangasem di Jawa sebelum Kab. Karangasem, Bali. Urutkan ulang: kecocokan kab/kota
     * paling atas, lalu kecamatan, kelurahan, dan provinsi.
     *
     * @param  array<int, array<string, ?string>>  $items
     * @return array<int, array<string, ?string>>
     */
    public static function rank(array $items, string $q): array
    {
        $words = array_filter(explode(' ', mb_strtolower($q)));
        $fields = ['city' => 8, 'district' => 4, 'subdistrict' => 2, 'province' => 1];

        $score = function (array $item) use ($words, $fields) {
            $total = 0;
            foreach ($words as $word) {
                foreach ($fields as $field => $weight) {
                    if (str_contains(mb_strtolower((string) $item[$field]), $word)) {
                        $total += $weight;
                    }
                }
            }

            return $total;
        };

        return collect($items)
            ->map(fn ($item, $i) => ['item' => $item, 'score' => $score($item), 'i' => $i])
            ->sort(fn ($a, $b) => [$b['score'], $a['i']] <=> [$a['score'], $b['i']])
            ->pluck('item')
            ->values()
            ->all();
    }
}
