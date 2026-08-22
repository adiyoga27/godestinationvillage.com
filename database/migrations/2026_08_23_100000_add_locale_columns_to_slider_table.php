<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('slider', function (Blueprint $table) {
            $table->text('title_id')->nullable()->after('title');
            $table->text('desc_id')->nullable()->after('desc');
        });

        $translations = [
            'Authentic Village Experience' => [
                'title_id' => 'Pengalaman Desa Autentik',
                'desc_id' => 'Rasakan pesona kehidupan desa dengan ikut serta dalam festival lokal, mencicipi kuliner tradisional, dan belajar praktik budaya yang telah turun-temurun dari penduduk setempat.',
            ],
            'Local Economic Improvement' => [
                'title_id' => 'Peningkatan Ekonomi Lokal',
                'desc_id' => 'Perjalanan Anda menjadi pendorong ekonomi desa — setiap kunjungan memberdayakan warga dan menggerakkan usaha lokal yang tumbuh bersama komunitas.',
            ],
            'Socially Responsible Tourism' => [
                'title_id' => 'Wisata yang Bertanggung Jawab Secara Sosial',
                'desc_id' => 'Berkelana sambil memberi dampak baik — mendukung mata pencaharian warga, melestarikan lingkungan, dan menjaga warisan budaya Bali.',
            ],
            'Worry Free Travel Service' => [
                'title_id' => 'Layanan Perjalanan Tanpa Khawatir',
                'desc_id' => 'Dari pemesanan hingga tiba di desa, layanan kami memastikan perjalanan Anda nyaman, aman, dan bebas khawatir.',
            ],
        ];

        foreach ($translations as $title => $data) {
            DB::table('slider')->where('title', $title)->update($data);
        }
    }

    public function down(): void
    {
        Schema::table('slider', function (Blueprint $table) {
            $table->dropColumn(['title_id', 'desc_id']);
        });
    }
};