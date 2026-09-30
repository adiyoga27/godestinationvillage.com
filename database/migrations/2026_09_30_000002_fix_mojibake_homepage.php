<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Perbaiki em-dash yang tersimpan sebagai mojibake "â€”" (E2 80 94 dibaca Latin1
        // lalu disimpan ulang UTF-8) akibat seed/input lama. Setelah ini admin bisa
        // edit normal via Administrator > Homepage Sections.
        $bad = "â€”"; // U+00E2 U+20AC U+201D
        $good = "—";  // U+2014

        foreach (DB::table('homepage_sections')->get(['id', 'eyebrow', 'eyebrow_id', 'title', 'title_id', 'subtitle', 'subtitle_id', 'badge_subtitle']) as $row) {
            $update = [];
            foreach (['eyebrow', 'eyebrow_id', 'title', 'title_id', 'subtitle', 'subtitle_id', 'badge_subtitle'] as $col) {
                $val = $row->$col;
                if (is_string($val) && str_contains($val, $bad)) {
                    $update[$col] = str_replace($bad, $good, $val);
                }
            }
            if ($update) {
                $update['updated_at'] = now();
                DB::table('homepage_sections')->where('id', $row->id)->update($update);
            }
        }

        foreach (DB::table('homepage_services')->get(['id', 'title', 'title_id']) as $row) {
            $update = [];
            foreach (['title', 'title_id'] as $col) {
                $val = $row->$col;
                if (is_string($val) && str_contains($val, $bad)) {
                    $update[$col] = str_replace($bad, $good, $val);
                }
            }
            if ($update) {
                $update['updated_at'] = now();
                DB::table('homepage_services')->where('id', $row->id)->update($update);
            }
        }
    }

    public function down(): void
    {
        // Tidak dikembalikan (perbaikan encoding satu arah).
    }
};
