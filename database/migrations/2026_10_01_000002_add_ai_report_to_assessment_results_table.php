<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            if (! Schema::hasColumn('assessment_results', 'ai_report')) {
                $table->json('ai_report')->nullable()->after('dimension_notes');
            }
            if (! Schema::hasColumn('assessment_results', 'report_status')) {
                $table->string('report_status', 20)->default('pending')->after('ai_report');
            }
            if (! Schema::hasColumn('assessment_results', 'report_error')) {
                $table->text('report_error')->nullable()->after('report_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->dropColumn(['ai_report', 'report_status', 'report_error']);
        });
    }
};
