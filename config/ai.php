<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AI Report Provider (Fase 3)
    |--------------------------------------------------------------------------
    | Driver didukung: 'deepseek' (OpenAI-compatible), 'claude' (Anthropic).
    | Semua panggilan hanya dari server. Key tidak pernah ke frontend.
    |
    | Nilai di bawah hanya cadangan: admin dapat mengubah driver aktif, API key & model
    | lewat Asesmen > Pengaturan AI (tabel ai_settings, key terenkripsi), yang menimpa
    | nilai ini saat aplikasi berjalan (lihat App\Support\AiSettings).
    */

    'driver' => env('AI_REPORT_DRIVER', 'deepseek'),

    'deepseek_key' => env('DEEPSEEK_API_KEY'),
    'deepseek_model' => env('DEEPSEEK_MODEL', env('AI_REPORT_MODEL', 'deepseek-flash')),
    'deepseek_base' => env('DEEPSEEK_BASE_URL', 'https://api.deepseek.com'),

    'claude_key' => env('ANTHROPIC_API_KEY'),
    'claude_model' => env('CLAUDE_MODEL', 'claude-opus-5-5'),
    // Admin API key (sk-ant-admin...) — opsional, hanya untuk membaca biaya (Cost Report).
    'claude_admin_key' => env('ANTHROPIC_ADMIN_KEY'),
    'claude_base' => env('ANTHROPIC_BASE_URL', 'https://api.anthropic.com'),
    'claude_version' => env('ANTHROPIC_VERSION', '2023-06-01'),

    'timeout' => (int) env('AI_REPORT_TIMEOUT', 120),

];
