<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_result_id')->constrained('assessment_results')->cascadeOnDelete();
            $table->string('code', 32)->unique(); // cth: ASM-20261001-XXXXXX
            $table->unsignedBigInteger('amount');
            $table->string('gateway', 20)->default('midtrans');
            $table->string('gateway_ref', 191)->nullable(); // snap token / transaction id
            $table->enum('status', ['pending', 'paid', 'failed', 'expired', 'refunded'])->default('pending');
            $table->string('payment_type', 50)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_orders');
    }
};
