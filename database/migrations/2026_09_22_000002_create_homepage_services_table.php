<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_services', function (Blueprint $table) {
            $table->id();
            $table->string('title', 191);
            $table->string('title_id', 191)->nullable();
            // Gambar item: boleh path asset bawaan (assets/...) atau
            // nama file di storage/homepage-services/.
            $table->string('image', 191)->nullable();
            $table->string('url', 191)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('homepage_services')->insert([
            ['title' => 'Internship Program', 'title_id' => 'Program Magang', 'image' => 'assets/customer/img/etc/internship.png', 'url' => 'services', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Tourism Planning & Strategy', 'title_id' => 'Perencanaan & Strategi Pariwisata', 'image' => 'assets/customer/img/etc/perencanaan.png', 'url' => 'services', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Portfolio', 'title_id' => 'Portofolio', 'image' => 'assets/customer/img/etc/portofolio.png', 'url' => 'services', 'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Project Management', 'title_id' => 'Manajemen Proyek', 'image' => 'assets/customer/img/etc/kajian.png', 'url' => 'services', 'sort_order' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Human Resources Development', 'title_id' => 'Pengembangan SDM', 'image' => 'assets/customer/img/etc/sdm.png', 'url' => 'services', 'sort_order' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Destination Branding & Digital Marketing', 'title_id' => 'Branding Destinasi & Digital Marketing', 'image' => 'assets/customer/img/etc/branding.png', 'url' => 'services', 'sort_order' => 6, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Consumer Trend & Tourism Insight', 'title_id' => 'Tren Konsumen & Wawasan Pariwisata', 'image' => 'assets/customer/img/etc/tren.png', 'url' => 'services', 'sort_order' => 7, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Research Analytics & Scientific Consulting', 'title_id' => 'Riset Analitik & Konsultasi Ilmiah', 'image' => 'assets/customer/img/etc/research.jpg', 'url' => 'services', 'sort_order' => 8, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_services');
    }
};
