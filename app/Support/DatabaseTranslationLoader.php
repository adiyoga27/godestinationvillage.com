<?php

namespace App\Support;

use App\Models\TranslationOverride;
use Illuminate\Contracts\Translation\Loader;
use Illuminate\Support\Facades\Cache;
use Throwable;

/**
 * Pembungkus loader terjemahan bawaan: teks dari lang/{locale}.json ditimpa
 * dengan perubahan dari admin (tabel translation_overrides, di-cache).
 */
class DatabaseTranslationLoader implements Loader
{
    public const CACHE_PREFIX = 'translation_overrides.';

    public function __construct(private Loader $files)
    {
    }

    public function load($locale, $group, $namespace = null)
    {
        $lines = $this->files->load($locale, $group, $namespace);

        if ($group === '*' && $namespace === '*') {
            $lines = array_merge($lines, self::overrides($locale));
        }

        return $lines;
    }

    /** @return array<string, string> kunci => teks yang diubah admin */
    public static function overrides(string $locale): array
    {
        try {
            return Cache::rememberForever(self::CACHE_PREFIX.$locale, fn () => TranslationOverride::where('locale', $locale)
                ->pluck('value', 'key')
                ->all());
        } catch (Throwable) {
            // Tabel belum ada (mis. sebelum migrate) → pakai teks dari file saja.
            return [];
        }
    }

    public static function flush(): void
    {
        foreach (Locales::SUPPORTED as $locale) {
            Cache::forget(self::CACHE_PREFIX.$locale);
        }

        // Teks yang sudah dimuat di proses ini (mis. queue worker) ikut dimuat ulang.
        if (app()->resolved('translator')) {
            app('translator')->setLoaded([]);
        }
    }

    public function addNamespace($namespace, $hint)
    {
        $this->files->addNamespace($namespace, $hint);
    }

    public function addJsonPath($path)
    {
        $this->files->addJsonPath($path);
    }

    public function namespaces()
    {
        return $this->files->namespaces();
    }
}
