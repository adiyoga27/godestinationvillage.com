<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Harga awal per Brief §5 (admin dapat mengubah tanpa deploy).
     */
    public function up(): void
    {
        DB::table('assessment_tracks')->where('slug', 'pariwisata')->update(['price' => 199000]);
        DB::table('assessment_tracks')->where('slug', 'ekonomi-desa')->update(['price' => 199000]);
        DB::table('assessment_tracks')->where('slug', 'regeneratif')->update(['price' => 299000]);
        DB::table('assessment_tracks')->where('slug', 'daya-saing-destinasi')->update(['price' => 499000]);
    }

    public function down(): void
    {
        // Harga dikelola admin; tidak di-rollback otomatis.
    }
};
