<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Sinkronisasi Instagram
    |--------------------------------------------------------------------------
    | Isi INSTAGRAM_ACCESS_TOKEN + INSTAGRAM_USER_ID di .env untuk memakai
    | Instagram Graph API (akun Business/Creator yang ditautkan ke Meta app).
    | Tanpa token, sinkronisasi mencoba membaca feed publik (best-effort).
    */

    'username' => env('INSTAGRAM_USERNAME', 'godevi.consulting'),

    'access_token' => env('INSTAGRAM_ACCESS_TOKEN'),

    'user_id' => env('INSTAGRAM_USER_ID'),

    'limit' => (int) env('INSTAGRAM_SYNC_LIMIT', 12),

    'graph_api_base' => 'https://graph.instagram.com/v21.0',
];