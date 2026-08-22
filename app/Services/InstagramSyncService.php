<?php

namespace App\Services;

use App\Models\Instragram;
use Illuminate\Support\Facades\Http;

class InstagramSyncService
{
    public function sync(): array
    {
        $posts = config('instagram.access_token')
            ? $this->fetchViaGraphApi()
            : $this->fetchViaPublicScrape();

        if (empty($posts)) {
            return [
                'status' => 'empty',
                'message' => config('instagram.access_token')
                    ? 'Tidak ada postingan baru dari API Instagram.'
                    : 'Feed publik Instagram belum dapat diakses tanpa login. Isi INSTAGRAM_ACCESS_TOKEN di .env untuk sinkronisasi otomatis.',
            ];
        }

        $created = 0;
        $updated = 0;

        foreach ($posts as $post) {
            if (Instragram::where('url', $post['url'])->exists()) {
                Instragram::where('url', $post['url'])->update([
                    'name' => $post['name'],
                    'is_active' => 1,
                ]);
                $updated++;
            } else {
                Instragram::create([
                    'name' => $post['name'],
                    'url' => $post['url'],
                    'is_active' => 1,
                ]);
                $created++;
            }
        }

        return [
            'status' => 'ok',
            'message' => "Sinkronisasi selesai — {$created} postingan baru, {$updated} diperbarui.",
            'created' => $created,
            'updated' => $updated,
        ];
    }

    private function fetchViaGraphApi(): array
    {
        $response = Http::timeout(20)->acceptJson()->get(
            config('instagram.graph_api_base') . '/' . config('instagram.user_id') . '/media',
            [
                'fields' => 'id,permalink,caption,timestamp,media_type',
                'limit' => config('instagram.limit', 12),
                'access_token' => config('instagram.access_token'),
            ]
        );

        if ($response->failed()) {
            return [];
        }

        return collect($response->json('data') ?? [])
            ->filter(fn ($p) => !empty($p['permalink']))
            ->map(fn ($p) => [
                'url' => $p['permalink'],
                'name' => mb_substr(strip_tags($p['caption'] ?? ''), 0, 220) ?: 'Instagram post',
            ])
            ->values()
            ->all();
    }

    private function fetchViaPublicScrape(): array
    {
        $html = Http::timeout(20)
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36',
            ])
            ->get('https://www.instagram.com/' . config('instagram.username'))
            ->body();

        preg_match_all('/"shortcode"\s*:\s*"([A-Za-z0-9_-]{5,})"/', $html, $matches);
        $shortcodes = array_unique($matches[1] ?? []);

        return collect($shortcodes)
            ->take(config('instagram.limit', 12))
            ->map(fn ($shortcode) => [
                'url' => 'https://www.instagram.com/p/' . $shortcode . '/',
                'name' => 'Instagram post @' . config('instagram.username'),
            ])
            ->values()
            ->all();
    }
}