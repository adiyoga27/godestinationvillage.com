<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Profil usaha/koperasi untuk jalur Ekonomi Desa.
     */
    public function up(): void
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->string('business_type')->nullable()->after('organization');
            $table->string('business_sector')->nullable()->after('business_type');
            $table->unsignedInteger('member_count')->nullable()->after('business_sector');
        });
    }

    public function down(): void
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->dropColumn(['business_type', 'business_sector', 'member_count']);
        });
    }
};
