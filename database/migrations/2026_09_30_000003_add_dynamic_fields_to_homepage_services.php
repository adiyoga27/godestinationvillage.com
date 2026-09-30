<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homepage_services', function (Blueprint $table) {
            // Isi modal per item (EN/ID). Kosong = kartu jadi link ke url.
            $table->text('desc')->nullable()->after('title_id');
            $table->text('desc_id')->nullable()->after('desc');
            // Kontak khusus per item untuk tombol modal (null = pakai default global).
            $table->string('phone', 50)->nullable()->after('url');
            $table->string('whatsapp', 50)->nullable()->after('phone');
            // Brosur/file lampiran untuk tombol Download di modal.
            $table->string('file', 191)->nullable()->after('whatsapp');
            // Tombol custom bebas: JSON [{label, label_id, url}].
            $table->json('buttons')->nullable()->after('file');
        });

        // Backfill deskripsi modal dari lang files (perilaku lama di services.blade.php).
        $en = $this->langMap('en');
        $id = $this->langMap('id');
        $modalByTitle = [
            'Tourism Planning & Strategy' => 'modal_planning',
            'Project Management' => 'modal_project',
            'Research Analytics & Scientific Consulting' => 'modal_research',
            'Internship Program' => 'modal_internship',
            'Human Resources Development' => 'modal_sdm',
            'Destination Branding & Digital Marketing' => 'modal_branding',
            'Consumer Trend & Tourism Insight' => 'modal_tren',
        ];

        foreach (DB::table('homepage_services')->get(['id', 'title']) as $row) {
            $key = $modalByTitle[$row->title] ?? null;
            if ($key) {
                DB::table('homepage_services')->where('id', $row->id)->update([
                    'desc' => $en[$key] ?? null,
                    'desc_id' => $id[$key] ?? null,
                    'updated_at' => now(),
                ]);
            }
        }

        // Portfolio tidak punya deskripsi modal: arahkan langsung ke halaman portofolio
        // (sebelumnya hardcoded v-portofolio di services.blade.php, tapi 'services' di homepage).
        DB::table('homepage_services')->where('title', 'Portfolio')->update([
            'url' => 'v-portofolio',
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::table('homepage_services', function (Blueprint $table) {
            $table->dropColumn(['desc', 'desc_id', 'phone', 'whatsapp', 'file', 'buttons']);
        });
    }

    protected function langMap(string $locale): array
    {
        $path = base_path('lang/'.$locale.'.json');
        if (! is_file($path)) {
            return [];
        }

        return json_decode((string) file_get_contents($path), true) ?: [];
    }
};
