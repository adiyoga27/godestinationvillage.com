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
        Schema::table('assessment_tracks', function (Blueprint $table) {
            $table->unsignedBigInteger('price')->default(199000)->after('estimated_minutes');
            $table->json('scale_labels')->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_tracks', function (Blueprint $table) {
            $table->dropColumn(['price', 'scale_labels']);
        });
    }
};
