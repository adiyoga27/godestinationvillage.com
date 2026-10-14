<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Asesmen dua bahasa: kolom utama tetap Bahasa Indonesia, versi English di kolom *_en
 * (kosong → tampil versi Indonesia). Laporan AI English disimpan terpisah di ai_report_en.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessment_tracks', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->string('tagline_en')->nullable()->after('tagline');
            $table->text('description_en')->nullable()->after('description');
            $table->string('target_audience_en')->nullable()->after('target_audience');
        });

        Schema::table('assessment_questions', function (Blueprint $table) {
            $table->string('dimension_en')->nullable()->after('dimension');
            $table->text('question_en')->nullable()->after('question');
            $table->text('help_text_en')->nullable()->after('help_text');
        });

        Schema::table('assessment_results', function (Blueprint $table) {
            // Bahasa responden saat mengisi (untuk email & bahasa awal laporan).
            $table->string('locale', 5)->default('id')->after('source');
            $table->json('ai_report_en')->nullable()->after('ai_report');
        });
    }

    public function down(): void
    {
        Schema::table('assessment_tracks', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'tagline_en', 'description_en', 'target_audience_en']);
        });
        Schema::table('assessment_questions', function (Blueprint $table) {
            $table->dropColumn(['dimension_en', 'question_en', 'help_text_en']);
        });
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->dropColumn(['locale', 'ai_report_en']);
        });
    }
};
