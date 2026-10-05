<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Teks website yang diubah dari admin (Pengaturan > Kelola Website > Bahasa Website).
 * Menimpa nilai bawaan di lang/{locale}.json tanpa mengubah file (file tetap di git).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translation_overrides', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 5);
            $table->char('key_hash', 64);
            $table->text('key');
            $table->text('value');
            $table->timestamps();

            $table->unique(['locale', 'key_hash']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translation_overrides');
    }
};
