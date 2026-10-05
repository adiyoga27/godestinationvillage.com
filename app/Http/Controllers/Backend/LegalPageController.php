<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LegalPage;
use Illuminate\Http\Request;

/** Pengaturan > Kelola Website > Syarat & Ketentuan (halaman /term). */
class LegalPageController extends Controller
{
    /** key => URL halaman publik */
    public const PAGES = ['terms' => '/term'];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            abort_unless($request->user()->role_id == 1, 403);

            return $next($request);
        });
    }

    public function edit(string $key)
    {
        $page = LegalPage::where('key', $key)->firstOrFail();
        $publicUrl = self::PAGES[$key] ?? null;

        return view('backend.legal_pages.edit', compact('page', 'publicUrl'));
    }

    public function update(Request $request, string $key)
    {
        $page = LegalPage::where('key', $key)->firstOrFail();

        $page->fill($request->validate([
            'title' => 'required|string|max:191',
            'title_id' => 'nullable|string|max:191',
            'content' => 'required|string',
            'content_id' => 'nullable|string',
            'last_updated' => 'nullable|date',
        ], [], [
            'title' => 'Judul (EN)',
            'title_id' => 'Judul (ID)',
            'content' => 'Isi (EN)',
            'content_id' => 'Isi (ID)',
            'last_updated' => 'Tanggal pembaruan',
        ]));

        // Isi berubah tapi tanggal tidak disentuh → tanggal "terakhir diperbarui" = hari ini.
        if ($page->isDirty(['content', 'content_id']) && ! $page->isDirty('last_updated')) {
            $page->last_updated = today();
        }
        $page->save();

        return redirect()->route('legal-pages.edit', $key)->with('status', 'Halaman berhasil disimpan');
    }
}
