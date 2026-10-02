<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sumber input (guest / admin), admin penginput, dan approval manual pembayaran.
     */
    public function up(): void
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->string('source', 20)->default('guest')->after('track_id');
            $table->unsignedBigInteger('created_by')->nullable()->after('source');
            $table->unsignedBigInteger('approved_by')->nullable()->after('unlocked_at');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->string('approval_note', 500)->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->dropColumn(['source', 'created_by', 'approved_by', 'approved_at', 'approval_note']);
        });
    }
};
