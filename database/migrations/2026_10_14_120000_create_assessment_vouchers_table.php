<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Kode voucher diskon asesmen (gratis / persen / potongan nominal) + jejak diskon di invoice.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 40)->unique();
            $table->string('description')->nullable();
            // free = 100%, percent = value %, fixed = potongan Rp value.
            $table->enum('type', ['free', 'percent', 'fixed'])->default('percent');
            $table->unsignedBigInteger('value')->default(0);
            // null = berlaku untuk semua jalur.
            $table->json('track_ids')->nullable();
            // null = tanpa batas pemakaian.
            $table->unsignedInteger('max_uses')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable(); // users.id (int) — tanpa FK, seperti assessment_results
            $table->timestamps();
        });

        Schema::table('assessment_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('original_amount')->nullable()->after('amount');
            $table->unsignedBigInteger('discount_amount')->default(0)->after('original_amount');
            $table->foreignId('voucher_id')->nullable()->after('discount_amount')->constrained('assessment_vouchers')->nullOnDelete();
            $table->string('voucher_code', 40)->nullable()->after('voucher_id');
        });

        // Invoice lama yang diganti karena voucher dipakai/dilepas → 'cancelled'.
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE assessment_orders MODIFY status ENUM('pending','paid','failed','expired','refunded','cancelled') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::table('assessment_orders')->where('status', 'cancelled')->update(['status' => 'failed']);
            DB::statement("ALTER TABLE assessment_orders MODIFY status ENUM('pending','paid','failed','expired','refunded') NOT NULL DEFAULT 'pending'");
        }

        Schema::table('assessment_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('voucher_id');
            $table->dropColumn(['original_amount', 'discount_amount', 'voucher_code']);
        });

        Schema::dropIfExists('assessment_vouchers');
    }
};
