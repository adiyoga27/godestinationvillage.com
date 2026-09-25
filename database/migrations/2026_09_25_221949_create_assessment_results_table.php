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
        Schema::create('assessment_results', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('track_id')->constrained('assessment_tracks')->cascadeOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('organization')->nullable();
            $table->json('answers')->nullable();
            $table->json('dimension_scores')->nullable();
            $table->decimal('total_score', 5, 2)->default(0);
            $table->string('band')->nullable();
            $table->enum('status', ['baru', 'dihubungi', 'selesai'])->default('baru');
            $table->unsignedBigInteger('pic_team_id')->nullable();
            $table->text('internal_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_results');
    }
};
