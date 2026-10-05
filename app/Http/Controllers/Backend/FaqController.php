<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

/** Pengaturan > Kelola Website > FAQ (halaman /faq). */
class FaqController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            abort_unless($request->user()->role_id == 1, 403);

            return $next($request);
        });
    }

    public function index()
    {
        $categories = FaqCategory::with('faqs')->orderBy('sort_order')->orderBy('id')->get();

        return view('backend.faqs.index', compact('categories'));
    }

    public function create(Request $request)
    {
        $categories = $this->categoryOptions();
        if ($categories->isEmpty()) {
            return redirect()->route('faq-categories.create')->with('error', 'Buat kategori FAQ terlebih dahulu.');
        }
        $faq = new Faq([
            'faq_category_id' => $request->integer('category') ?: null,
            'is_active' => true,
            'sort_order' => ((int) Faq::where('faq_category_id', $request->integer('category'))->max('sort_order')) + 10,
        ]);

        return view('backend.faqs.create', compact('faq', 'categories'));
    }

    public function store(Request $request)
    {
        Faq::create($this->validated($request));

        return redirect()->route('faqs.index')->with('status', 'Pertanyaan FAQ berhasil ditambahkan');
    }

    public function edit($id)
    {
        $faq = Faq::findOrFail($id);
        $categories = $this->categoryOptions();

        return view('backend.faqs.edit', compact('faq', 'categories'));
    }

    public function update(Request $request, $id)
    {
        Faq::findOrFail($id)->update($this->validated($request));

        return redirect()->route('faqs.index')->with('status', 'Pertanyaan FAQ berhasil diperbarui');
    }

    public function destroy($id)
    {
        Faq::findOrFail($id)->delete();

        return redirect()->route('faqs.index')->with('status', 'Pertanyaan FAQ dihapus');
    }

    public function toggle($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->update(['is_active' => ! $faq->is_active]);

        return back()->with('status', $faq->is_active ? 'Pertanyaan ditampilkan' : 'Pertanyaan disembunyikan');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'faq_category_id' => 'required|exists:faq_categories,id',
            'question' => 'required|string|max:500',
            'question_id' => 'nullable|string|max:500',
            'answer' => 'required|string',
            'answer_id' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ], [], [
            'faq_category_id' => 'Kategori',
            'question' => 'Pertanyaan (EN)',
            'question_id' => 'Pertanyaan (ID)',
            'answer' => 'Jawaban (EN)',
            'answer_id' => 'Jawaban (ID)',
        ]);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['is_active'] = $request->boolean('is_active', true);

        return $validated;
    }

    private function categoryOptions()
    {
        return FaqCategory::orderBy('sort_order')->orderBy('id')->pluck('title', 'id');
    }
}
