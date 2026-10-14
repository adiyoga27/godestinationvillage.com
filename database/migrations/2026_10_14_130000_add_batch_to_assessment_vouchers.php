<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Voucher yang dibuat sekaligus (bulk) dikelompokkan per batch untuk difilter, diunduh & dinonaktifkan bersama. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessment_vouchers', function (Blueprint $table) {
            $table->string('batch', 60)->nullable()->after('code')->index();
        });
    }

    public function down(): void
    {
        Schema::table('assessment_vouchers', function (Blueprint $table) {
            $table->dropIndex(['batch']);
            $table->dropColumn('batch');
        });
    }
};
