<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tag paket (Culture, Adventure, Wellness) = kartu "Explore Village" di beranda.
 * Tambah versi Indonesia, link tujuan & urutan agar bisa dikelola dari admin.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tag_category', function (Blueprint $table) {
            $table->string('name_id')->nullable()->after('name');
            $table->text('desc_id')->nullable()->after('desc');
            $table->string('url')->nullable()->after('image');
            $table->unsignedInteger('sort_order')->default(0)->after('url');
        });

        DB::table('tag_category')->update(['sort_order' => DB::raw('id * 10')]);
    }

    public function down(): void
    {
        Schema::table('tag_category', function (Blueprint $table) {
            $table->dropColumn(['name_id', 'desc_id', 'url', 'sort_order']);
        });
    }
};
