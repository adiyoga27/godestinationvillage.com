<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 50)->unique();
            $table->string('label', 100);
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Nilai awal diambil dari data yang selama ini hardcoded di website,
        // agar tampilan tidak berubah dan tinggal diubah dari admin.
        DB::table('site_settings')->insert([
            ['key' => 'address', 'label' => 'Alamat', 'value' => 'Jl Kroya No 1, Denpasar, Bali', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'phone', 'label' => 'Telepon (format link, cth: +6281234567890)', 'value' => '+6281997674778', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'phone_display', 'label' => 'Telepon (tampilan, cth: +62 812-3456-7890)', 'value' => '+62 819-9767-4778', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'email', 'label' => 'Email', 'value' => 'hello@godestinationvillage.com', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'facebook', 'label' => 'URL Facebook', 'value' => 'https://www.facebook.com/godestinationvillage/', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'instagram', 'label' => 'URL Instagram', 'value' => 'https://www.instagram.com/godestinationvillage/', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'youtube', 'label' => 'URL YouTube', 'value' => 'https://www.youtube.com/channel/UCule1cMKmK4RKh_n-Rrx81A', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
