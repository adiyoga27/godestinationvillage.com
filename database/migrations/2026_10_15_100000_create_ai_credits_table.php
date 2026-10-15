<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Kredit layanan AI per penyedia. Saldo, pengali tarif, dan harga token per 1 juta (US$)
 * diatur langsung di database. Setiap panggilan AI mengurangi saldo sebesar
 * biaya token × multiplier; biaya asli dan jumlah yang dibebankan dicatat di ai_usage_logs.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_credits', function (Blueprint $table) {
            $table->id();
            $table->string('driver', 20)->unique();
            $table->decimal('balance', 14, 4)->default(0);
            $table->decimal('multiplier', 8, 4)->default(2);
            $table->decimal('price_input', 10, 4)->default(0);
            $table->decimal('price_cache_hit', 10, 4)->default(0);
            $table->decimal('price_output', 10, 4)->default(0);
            $table->timestamps();
        });

        Schema::table('ai_usage_logs', function (Blueprint $table) {
            $table->decimal('cost', 12, 6)->default(0)->after('total_tokens');
            $table->decimal('charged', 12, 6)->default(0)->after('cost');
        });

        $now = now();
        DB::table('ai_credits')->insert([
            ['driver' => 'deepseek', 'balance' => 0, 'multiplier' => 2, 'price_input' => 0.28, 'price_cache_hit' => 0.028, 'price_output' => 0.42, 'created_at' => $now, 'updated_at' => $now],
            ['driver' => 'claude', 'balance' => 0, 'multiplier' => 2, 'price_input' => 3, 'price_cache_hit' => 0.30, 'price_output' => 15, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::table('ai_usage_logs', fn (Blueprint $table) => $table->dropColumn(['cost', 'charged']));
        Schema::dropIfExists('ai_credits');
    }
};
