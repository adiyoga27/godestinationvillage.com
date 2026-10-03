<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Riwayat email asesmen (invoice, pembayaran berhasil, hasil & strategi).
     */
    public function up(): void
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->json('email_log')->nullable()->after('ai_meta');
        });
    }

    public function down(): void
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->dropColumn('email_log');
        });
    }
};
