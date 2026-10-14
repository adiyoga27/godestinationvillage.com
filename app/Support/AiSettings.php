<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Pengaturan AI yang diubah dari admin (tabel ai_settings) menimpa config('ai.*').
 * Kolom kosong di admin → tetap memakai nilai .env. API key disimpan terenkripsi (APP_KEY).
 */
class AiSettings
{
    private const CACHE_KEY = 'ai-settings';

    public const DRIVERS = ['deepseek' => 'DeepSeek', 'claude' => 'Claude (Anthropic)'];

    /** key => terenkripsi? */
    public const FIELDS = [
        'driver' => false,
        'deepseek_key' => true,
        'deepseek_model' => false,
        'claude_key' => true,
        'claude_model' => false,
        'claude_admin_key' => true,
        'timeout' => false,
    ];

    /** Nilai config sebelum ditimpa pengaturan admin (= dari .env / default). */
    private static ?array $envDefaults = null;

    /** Muat pengaturan admin ke config. Dipanggil saat boot; aman bila tabel belum ada. */
    public static function apply(): void
    {
        self::$envDefaults ??= collect(array_keys(self::FIELDS))->mapWithKeys(fn ($k) => [$k => config('ai.'.$k)])->all();
        foreach (self::$envDefaults as $key => $value) {
            config(['ai.'.$key => $value]);
        }

        try {
            $values = Cache::rememberForever(self::CACHE_KEY, fn () => DB::table('ai_settings')->pluck('value', 'key')->all());
        } catch (\Throwable $e) {
            return;
        }

        foreach ($values as $key => $value) {
            if (! array_key_exists($key, self::FIELDS) || $value === null || $value === '') {
                continue;
            }
            if (self::FIELDS[$key]) {
                try {
                    $value = Crypt::decryptString($value);
                } catch (\Throwable $e) {
                    Log::warning("Pengaturan AI '{$key}' tidak bisa didekripsi (APP_KEY berubah?) — memakai nilai .env.");

                    continue;
                }
            }
            config(['ai.'.$key => $key === 'timeout' ? (int) $value : $value]);
        }
    }

    /**
     * Simpan pengaturan. Untuk key rahasia: null = tidak diubah, '' = hapus (kembali ke .env).
     *
     * @param  array<string, string|null>  $values
     */
    public static function save(array $values, ?int $userId = null): void
    {
        foreach ($values as $key => $value) {
            if (! array_key_exists($key, self::FIELDS) || (self::FIELDS[$key] && $value === null)) {
                continue;
            }
            $value = trim((string) $value);
            DB::table('ai_settings')->updateOrInsert(['key' => $key], [
                'value' => $value === '' ? null : (self::FIELDS[$key] ? Crypt::encryptString($value) : $value),
                'updated_by' => $userId,
                'updated_at' => now(),
                'created_at' => now(),
            ]);
        }

        Cache::forget(self::CACHE_KEY);
        self::apply();
    }

    /** Asal nilai sebuah key: 'admin' (diisi di panel), 'env', atau null (kosong). */
    public static function source(string $key): ?string
    {
        $stored = DB::table('ai_settings')->where('key', $key)->value('value');
        if ($stored !== null && $stored !== '') {
            return 'admin';
        }

        return filled(self::$envDefaults[$key] ?? config('ai.'.$key)) ? 'env' : null;
    }

    /** Tampilkan key tersamar, mis. "sk-…a1b2". */
    public static function mask(?string $secret): ?string
    {
        if (blank($secret)) {
            return null;
        }

        return mb_substr($secret, 0, 6).'…'.mb_substr($secret, -4);
    }
}
