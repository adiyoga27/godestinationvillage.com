<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Arsip lengkap per hasil: ringkasan skor terhitung, prompt yang dikirim ke AI,
     * dan metadata panggilan AI (driver, model, usage, percobaan, error).
     */
    public function up(): void
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->json('computed_result')->nullable()->after('band');
            $table->longText('ai_prompt')->nullable()->after('ai_report');
            $table->json('ai_meta')->nullable()->after('ai_prompt');
        });
    }

    public function down(): void
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->dropColumn(['computed_result', 'ai_prompt', 'ai_meta']);
        });
    }
};
