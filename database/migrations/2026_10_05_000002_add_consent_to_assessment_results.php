<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bukti persetujuan responden: S&K + Kebijakan Privasi (wajib),
     * boleh dihubungi tim & data anonim untuk riset (opsional).
     */
    public function up(): void
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->timestamp('consent_terms_at')->nullable()->after('profile_description');
            $table->boolean('consent_contact')->default(false)->after('consent_terms_at');
            $table->boolean('consent_research')->default(false)->after('consent_contact');
            $table->string('consent_ip', 45)->nullable()->after('consent_research');
        });
    }

    public function down(): void
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->dropColumn(['consent_terms_at', 'consent_contact', 'consent_research', 'consent_ip']);
        });
    }
};
