<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homepage_sections', function (Blueprint $table) {
            // Badge kecil di section Tentang / Why GODEVI (cth: SEE + Sustainability...).
            // Sengaja tanpa varian _id: selalu tampil English, tidak di-translate.
            $table->string('badge_title', 191)->nullable()->after('subtitle_id');
            $table->string('badge_subtitle', 191)->nullable()->after('badge_title');
        });

        // Default awal untuk section about bila masih kosong.
        DB::table('homepage_sections')->where('key', 'about')->whereNull('badge_title')->update([
            'badge_title' => 'SEE',
            'badge_subtitle' => 'Sustainability · Empowerment · Entrepreneurship',
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::table('homepage_sections', function (Blueprint $table) {
            $table->dropColumn(['badge_title', 'badge_subtitle']);
        });
    }
};
