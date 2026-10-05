<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * GODEVI kini menjangkau seluruh Indonesia — ganti copy "Bali" pada konten
 * yang tersimpan di DB (hero halaman, section beranda, slider). Alamat kantor
 * dan cerita logo (Jalak Bali) tidak disentuh.
 */
return new class extends Migration
{
    private array $replacements = [
        // EN
        'Bali Homestay & Village Stay' => 'Village Homestay & Stay',
        'Bali Tour Packages' => 'Village Tour Packages',
        'Explore Villages in Bali' => 'Explore Villages in Indonesia',
        'Go Destination Village · Bali' => 'Go Destination Village · Indonesia',
        "Tourism that gives back to Bali's villages" => "Tourism that gives back to Indonesia's villages",
        "world of Bali's villages" => "world of Indonesia's villages",
        "Bali's cultural heritage" => "Indonesia's cultural heritage",
        'that make Bali extraordinary' => 'that make Indonesia extraordinary',
        'hosted with Balinese warmth' => 'hosted with Indonesian warmth',
        'Balinese' => 'Indonesian',
        'across Bali' => 'across Indonesia',
        'in Bali' => 'in Indonesia',
        // ID
        'desa-desa Bali' => 'desa-desa Indonesia',
        'Desa-desa Bali' => 'Desa-desa Indonesia',
        'keramahan khas Bali' => 'keramahan khas Nusantara',
        'warisan budaya Bali' => 'warisan budaya Indonesia',
        'tradisi Bali' => 'tradisi Nusantara',
        'membuat Bali luar biasa' => 'membuat Indonesia luar biasa',
        'seluruh Bali' => 'seluruh Indonesia',
        'di Bali' => 'di Indonesia',
    ];

    private array $tables = ['page_heroes', 'homepage_sections', 'slider'];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach (DB::table($table)->get() as $row) {
                $changes = [];
                foreach ((array) $row as $column => $value) {
                    if (! is_string($value) || stripos($value, 'Bali') === false) {
                        continue;
                    }
                    $new = strtr($value, $this->replacements);
                    if ($new !== $value) {
                        $changes[$column] = $new;
                    }
                }
                if ($changes) {
                    DB::table($table)->where('id', $row->id)->update($changes);
                }
            }
        }
    }

    public function down(): void
    {
        // Perubahan copy tidak dikembalikan.
    }
};
