<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tombol "Lihat Semua Desa" sempat diisi link file di laptop
 * (file:///C:/Users/ASUS/Downloads/GODEVI-Booklet.pdf). Ganti ke PDF di server;
 * link file:// lain dikosongkan (tombol kembali ke default).
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (['homepage_sections', 'slider', 'homepage_services'] as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach (['button_url', 'button2_url', 'url'] as $column) {
                if (! Schema::hasColumn($table, $column)) {
                    continue;
                }

                DB::table($table)->where($column, 'like', 'file:%')->get()->each(function ($row) use ($table, $column) {
                    $new = stripos($row->{$column}, 'GODEVI-Booklet.pdf') !== false
                        ? 'storage/documents/GODEVI-Booklet.pdf'
                        : null;
                    DB::table($table)->where('id', $row->id)->update([$column => $new]);
                });
            }
        }
    }

    public function down(): void
    {
        //
    }
};
