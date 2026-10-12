<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->string('name_id', 191)->nullable();
            $table->text('desc')->nullable();
            $table->text('desc_id')->nullable();
            // Logo: boleh path asset bawaan (assets/...) atau
            // nama file di storage/partners/.
            $table->string('logo', 191)->nullable();
            $table->string('url', 191)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $dir = 'assets/customer/frontdata/images/';
        $rows = [
            ['Tohpati Business Incubator', 'Inkubator Bisnis Tohpati', 'Business incubation partner supporting tourism village entrepreneurship.', 'Mitra inkubasi bisnis yang mendukung kewirausahaan desa wisata.', 'inbistohpati.png', null],
            ['Bali Tourism Village Communication Forum', 'Forum Komunikasi Desa Wisata Bali', 'Collaboration forum connecting tourism villages across Bali.', 'Forum kolaborasi yang menghubungkan desa wisata di seluruh Bali.', 'dewibali.png', null],
            ['Himpunan Mahasiswa Pariwisata Indonesia', null, 'National tourism student association (HMPI).', 'Asosiasi mahasiswa pariwisata nasional (HMPI).', 'HMPIndonesia.png', 'https://hmpiofficial.org'],
            ['EZZEGO.APP', null, 'Digital travel technology partner.', 'Mitra teknologi perjalanan digital.', 'ezeego.png', 'https://EzeeGo.app'],
            ['Institut Pariwisata dan Bisnis Internasional', null, 'International tourism and business institute (IPBI).', 'Institut pariwisata dan bisnis internasional (IPBI).', 'IPBI.png', 'https://www.ipb-intl.ac.id'],
            ['Universitas Udayana', null, 'Academic partner for tourism research and development.', 'Mitra akademik untuk penelitian dan pengembangan pariwisata.', 'unud.png', 'https://www.unud.ac.id'],
            ['Buleleng Homestay Association', 'Asosiasi Home Stay Buleleng', 'Homestay association of Buleleng regency.', 'Asosiasi home stay Kabupaten Buleleng.', 'BUHSA.png', 'https://www.instagram.com/buleleng_homestay'],
        ];

        foreach ($rows as $i => [$name, $nameId, $desc, $descId, $logo, $url]) {
            DB::table('partners')->insert([
                'name' => $name,
                'name_id' => $nameId,
                'desc' => $desc,
                'desc_id' => $descId,
                'logo' => $dir.$logo,
                'url' => $url,
                'sort_order' => $i + 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
