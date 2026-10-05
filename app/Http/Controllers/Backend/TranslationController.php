<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\TranslationOverride;
use App\Support\DatabaseTranslationLoader;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Pengaturan > Kelola Website > Bahasa Website.
 * Daftar teks = kunci di lang/id.json & lang/en.json. Perubahan disimpan di
 * translation_overrides (file JSON tidak diubah, jadi aman saat deploy).
 */
class TranslationController extends Controller
{
    private const LOCALES = ['id' => 'Indonesia', 'en' => 'English'];

    private const PER_PAGE = 25;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            abort_unless($request->user()->role_id == 1, 403);

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $filter = $request->query('filter', 'all');
        $rows = $this->rows();

        $filtered = $rows->filter(function ($row) use ($search, $filter) {
            if ($filter === 'changed' && ! $row['changed']) {
                return false;
            }
            if ($filter === 'untranslated' && ! $row['untranslated']) {
                return false;
            }
            if ($search === '') {
                return true;
            }

            return Str::contains($row['key'].' '.$row['values']['id'].' '.$row['values']['en'], $search, true);
        })->values();

        $page = max(1, (int) $request->query('page', 1));
        $translations = new LengthAwarePaginator(
            $filtered->forPage($page, self::PER_PAGE)->values(),
            $filtered->count(),
            self::PER_PAGE,
            $page,
            ['path' => route('translations.index'), 'query' => $request->only(['q', 'filter'])]
        );

        return view('backend.translations.index', [
            'translations' => $translations,
            'locales' => self::LOCALES,
            'search' => $search,
            'filter' => $filter,
            'counts' => [
                'all' => $rows->count(),
                'changed' => $rows->where('changed', true)->count(),
                'untranslated' => $rows->where('untranslated', true)->count(),
            ],
        ]);
    }

    /** Simpan semua baris di halaman yang sedang dibuka. */
    public function update(Request $request)
    {
        $request->validate([
            'translations' => 'required|array',
            'translations.*.id' => 'nullable|string|max:20000',
            'translations.*.en' => 'nullable|string|max:20000',
        ]);

        $rows = $this->rows()->keyBy('hash');
        $changed = 0;

        DB::transaction(function () use ($request, $rows, &$changed) {
            foreach ($request->input('translations') as $hash => $values) {
                $row = $rows->get($hash);
                if (! $row) {
                    continue; // hanya kunci yang ada di file bahasa
                }

                foreach (array_keys(self::LOCALES) as $locale) {
                    if (! array_key_exists($locale, $values)) {
                        continue;
                    }
                    $value = str_replace("\r\n", "\n", (string) $values[$locale]);
                    $default = $row['defaults'][$locale];
                    $match = ['locale' => $locale, 'key_hash' => $hash];

                    // Kosong atau sama dengan teks bawaan → hapus override (kembali ke file).
                    if (trim($value) === '' || $value === $default) {
                        $changed += TranslationOverride::where($match)->get()->each->delete()->count();

                        continue;
                    }

                    if ($value !== $row['values'][$locale]) {
                        TranslationOverride::updateOrCreate($match, ['key' => $row['key'], 'value' => $value]);
                        $changed++;
                    }
                }
            }
        });

        DatabaseTranslationLoader::flush();

        return back()->with('status', $changed ? $changed.' teks berhasil disimpan' : 'Tidak ada perubahan');
    }

    /** Kembalikan satu teks ke bawaan file (semua bahasa). */
    public function reset(string $hash)
    {
        TranslationOverride::where('key_hash', $hash)->get()->each->delete();
        DatabaseTranslationLoader::flush();

        return back()->with('status', 'Teks dikembalikan ke bawaan');
    }

    /**
     * Semua kunci dari file bahasa + nilai bawaan & nilai aktif per bahasa.
     *
     * @return \Illuminate\Support\Collection<int, array>
     */
    private function rows()
    {
        $files = [];
        foreach (array_keys(self::LOCALES) as $locale) {
            $path = lang_path($locale.'.json');
            $files[$locale] = is_file($path) ? (json_decode(file_get_contents($path), true) ?: []) : [];
        }

        $overrides = TranslationOverride::all()->groupBy('locale')
            ->map(fn ($items) => $items->pluck('value', 'key_hash'));

        $keys = array_unique(array_merge(...array_map('array_keys', array_values($files))));
        sort($keys, SORT_NATURAL | SORT_FLAG_CASE);

        return collect($keys)->map(function ($key) use ($files, $overrides) {
            $hash = TranslationOverride::hashKey($key);
            $row = ['key' => $key, 'hash' => $hash, 'defaults' => [], 'values' => [], 'changed' => false];

            foreach (array_keys(self::LOCALES) as $locale) {
                $default = (string) ($files[$locale][$key] ?? $key);
                $override = $overrides->get($locale)?->get($hash);
                $row['defaults'][$locale] = $default;
                $row['values'][$locale] = $override ?? $default;
                $row['changed'] = $row['changed'] || $override !== null;
            }
            // Versi Indonesia masih sama persis dengan English → kemungkinan belum diterjemahkan.
            $row['untranslated'] = $row['values']['id'] === $row['values']['en'];

            return $row;
        });
    }
}
