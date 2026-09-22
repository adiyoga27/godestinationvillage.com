<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('homepage_sections')->insert([
            'key' => 'hero', 'name' => 'Hero', 'sort_order' => 0, 'is_active' => true,
            'eyebrow' => 'Go Destination Village · Bali', 'eyebrow_id' => 'Go Destination Village · Bali',
            'title' => null, 'title_id' => null, 'subtitle' => null, 'subtitle_id' => null,
            'image' => null,
            'button_label' => 'Explore Villages', 'button_label_id' => 'Jelajahi Desa',
            'button_url' => 'village',
            'button2_label' => 'View Tour Packages', 'button2_label_id' => 'Lihat Paket Wisata',
            'button2_url' => 'tour-packages',
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('homepage_sections')->where('key', 'hero')->delete();
    }
};
