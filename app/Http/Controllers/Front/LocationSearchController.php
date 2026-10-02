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
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 3) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $apiKey = (string) config('services.mengantar.api_key');
        if ($apiKey === '') {
            Log::warning('Location search tanpa MENGANTAR_API_KEY.');

            return response()->json(['success' => true, 'data' => []]);
        }

        $data = Cache::remember('mengantar_location_'.md5(mb_strtolower($q)), now()->addDay(), function () use ($q, $apiKey) {
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
                ])
                ->filter(fn ($item) => $item['province'] && $item['city'])
                ->values()
                ->all();
        });

        if ($data === null) {
            Cache::forget('mengantar_location_'.md5(mb_strtolower($q)));
            $data = [];
        }

        return response()->json(['success' => true, 'data' => $data]);
    }
}
