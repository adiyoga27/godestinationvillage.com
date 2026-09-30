<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_about_features', function (Blueprint $table) {
            $table->id();
            $table->string('title', 191);
            $table->string('title_id', 191)->nullable();
            $table->text('desc')->nullable();
            $table->text('desc_id')->nullable();
            // Gambar ikon opsional (nama file di storage/homepage-about-features).
            // Kosong = pakai ikon bawaan (check-circle).
            $table->string('image', 191)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('homepage_about_features')->insert([
            ['title' => 'Socially Responsible', 'title_id' => 'Bertanggung Jawab Sosial', 'desc' => 'Every experience supports local livelihoods and community growth.', 'desc_id' => 'Setiap pengalaman mendukung mata pencaharian lokal dan pertumbuhan komunitas.', 'image' => null, 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Community Empowerment', 'title_id' => 'Pemberdayaan Komunitas', 'desc' => 'A marketplace that champions fair trade and village entrepreneurs.', 'desc_id' => 'Marketplace yang mengusung perdagangan adil dan wirausahawan desa.', 'image' => null, 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Sustainable Tourism', 'title_id' => 'Pariwisata Berkelanjutan', 'desc' => 'Rooted in sustainability, balancing people, planet and prosperity.', 'desc_id' => 'Berakar pada keberlanjutan, menyeimbangkan manusia, planet, dan kesejahteraan.', 'image' => null, 'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Local Experiences', 'title_id' => 'Pengalaman Lokal', 'desc' => 'Genuine tours, homestays and events led by village communities.', 'desc_id' => 'Tur, homestay, dan acara autentik yang dipimpin komunitas desa.', 'image' => null, 'sort_order' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_about_features');
    }
};
