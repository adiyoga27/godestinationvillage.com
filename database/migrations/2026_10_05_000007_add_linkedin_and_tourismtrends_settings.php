<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Profil resmi tambahan untuk footer & JSON-LD Organization.sameAs.
 * LinkedIn dikosongkan — diisi dari admin (Pengaturan Website).
 */
return new class extends Migration
{
    private array $rows = [
        ['key' => 'linkedin', 'label' => 'URL LinkedIn', 'value' => null],
        ['key' => 'tourismtrends', 'label' => 'URL tourismtrends.org', 'value' => 'https://tourismtrends.org/'],
    ];

    public function up(): void
    {
        foreach ($this->rows as $row) {
            if (! DB::table('site_settings')->where('key', $row['key'])->exists()) {
                DB::table('site_settings')->insert($row + ['created_at' => now(), 'updated_at' => now()]);
            }
        }

        Cache::forget('site.settings'); // App\Helpers\Site::CACHE_KEY
    }

    public function down(): void
    {
        DB::table('site_settings')->whereIn('key', array_column($this->rows, 'key'))->delete();
    }
};
