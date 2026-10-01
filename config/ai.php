<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AI Report Provider (Fase 3)
    |--------------------------------------------------------------------------
    | Driver didukung: 'deepseek' (OpenAI-compatible), 'claude' (Anthropic).
    | Semua panggilan hanya dari server. Key tidak pernah ke frontend.
    */

    'driver' => env('AI_REPORT_DRIVER', 'deepseek'),

    'model' => env('AI_REPORT_MODEL', 'deepseek-flash'),

    'deepseek_key' => env('DEEPSEEK_API_KEY'),
    'deepseek_base' => env('DEEPSEEK_BASE_URL', 'https://api.deepseek.com'),

    'claude_key' => env('ANTHROPIC_API_KEY'),
    'claude_base' => env('ANTHROPIC_BASE_URL', 'https://api.anthropic.com'),
    'claude_version' => env('ANTHROPIC_VERSION', '2023-06-01'),

    'timeout' => (int) env('AI_REPORT_TIMEOUT', 120),

];
