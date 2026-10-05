<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

/** Kategori FAQ (grup akordeon di halaman /faq). Daftar kategori tampil di halaman FAQ admin. */
class FaqCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            abort_unless($request->user()->role_id == 1, 403);

            return $next($request);
        });
    }

    public function create()
    {
        $category = new FaqCategory([
            'is_active' => true,
            'sort_order' => ((int) FaqCategory::max('sort_order')) + 10,
        ]);

        return view('backend.faq_categories.create', compact('category'));
    }

    public function store(Request $request)
    {
        FaqCategory::create($this->validated($request));

        return redirect()->route('faqs.index')->with('status', 'Kategori FAQ berhasil ditambahkan');
    }

    public function edit($id)
    {
        $category = FaqCategory::findOrFail($id);

        return view('backend.faq_categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        FaqCategory::findOrFail($id)->update($this->validated($request));

        return redirect()->route('faqs.index')->with('status', 'Kategori FAQ berhasil diperbarui');
    }

    public function destroy($id)
    {
        $category = FaqCategory::withCount('faqs')->findOrFail($id);
        if ($category->faqs_count > 0) {
            return back()->with('error', 'Kategori masih berisi '.$category->faqs_count.' pertanyaan. Pindahkan atau hapus pertanyaannya dulu.');
        }
        $category->delete();

        return redirect()->route('faqs.index')->with('status', 'Kategori FAQ dihapus');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'title' => 'required|string|max:191',
            'title_id' => 'nullable|string|max:191',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ], [], ['title' => 'Nama Kategori (EN)', 'title_id' => 'Nama Kategori (ID)']);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['is_active'] = $request->boolean('is_active', true);

        return $validated;
    }
}
