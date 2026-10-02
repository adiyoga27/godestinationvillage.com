<?php

use App\Services\AssessmentPaymentService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Form identitas asesmen baru: email opsional, lokasi sampai kecamatan/desa (API Mengantar),
     * dan No. WA dinormalisasi agar bisa dipakai di halaman Cek Status.
     */
    public function up(): void
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });

        // Kolom bisa sudah ada di DB lokal yang pernah menjalankan versi awal fitur ini.
        if (! Schema::hasColumn('assessment_results', 'district')) {
            Schema::table('assessment_results', function (Blueprint $table) {
                $table->string('subdistrict')->nullable()->after('organization');
                $table->string('district')->nullable()->after('subdistrict');
            });
        }

        DB::table('assessment_results')->whereNotNull('phone')->orderBy('id')->each(function ($row) {
            $phone = AssessmentPaymentService::normalizePhone($row->phone);
            if ($phone !== $row->phone) {
                DB::table('assessment_results')->where('id', $row->id)->update(['phone' => $phone]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->dropColumn(['subdistrict', 'district']);
        });
    }
};
