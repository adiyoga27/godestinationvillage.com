<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->string('regency')->nullable()->after('organization');
            $table->string('province')->nullable()->after('regency');
            $table->text('profile_description')->nullable()->after('province');
            $table->json('dimension_notes')->nullable()->after('dimension_scores');
            $table->boolean('is_unlocked')->default(false)->after('band');
            $table->timestamp('unlocked_at')->nullable()->after('is_unlocked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->dropColumn(['regency', 'province', 'profile_description', 'dimension_notes', 'is_unlocked', 'unlocked_at']);
        });
    }
};
