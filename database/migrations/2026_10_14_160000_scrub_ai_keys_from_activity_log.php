<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Hapus API key AI yang sempat tercatat polos di activity_log (input form Pengaturan AI
 * sebelum field key dimasukkan ke daftar sensitif LogUserActivity).
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('activity_log')
            ->where('description', 'like', '%ai-settings%')
            ->where(fn ($q) => $q->where('properties', 'like', '%deepseek_key%')->orWhere('properties', 'like', '%claude_key%')->orWhere('properties', 'like', '%claude_admin_key%'))
            ->orderBy('id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    $props = json_decode($row->properties, true);
                    if (! is_array($props) || ! isset($props['input']) || ! is_array($props['input'])) {
                        continue;
                    }
                    unset($props['input']['deepseek_key'], $props['input']['claude_key'], $props['input']['claude_admin_key']);
                    DB::table('activity_log')->where('id', $row->id)->update(['properties' => json_encode($props)]);
                }
            });
    }

    public function down(): void
    {
        // Data yang dihapus tidak dikembalikan.
    }
};
