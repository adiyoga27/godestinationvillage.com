<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\CustomImage;
use App\Http\Controllers\Controller;
use App\Models\CategoryPackage;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Pengaturan > Kelola Website > Kartu Explore Village.
 * Kartu di section "Explore Village" beranda = tag paket (tabel tag_category),
 * juga dipakai sebagai pilihan "Tag Paket" di form Paket Wisata.
 */
class ExploreCardController extends Controller
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
        $cards = Tag::orderBy('sort_order')->orderBy('id')->get();
        $usage = CategoryPackage::selectRaw('tag_id, count(*) as total')->groupBy('tag_id')->pluck('total', 'tag_id');

        return view('backend.explore_cards.index', compact('cards', 'usage'));
    }

    public function create()
    {
        $card = new Tag(['status' => true, 'sort_order' => ((int) Tag::max('sort_order')) + 10]);

        return view('backend.explore_cards.create', compact('card'));
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request, true);
        $validated['image'] = CustomImage::storeFile($request->file('image'), 'tag')['name'];

        Tag::create($validated);

        return redirect()->route('explore-cards.index')->with('status', 'Kartu berhasil ditambahkan');
    }

    public function edit($id)
    {
        $card = Tag::findOrFail($id);

        return view('backend.explore_cards.edit', compact('card'));
    }

    public function update(Request $request, $id)
    {
        $card = Tag::findOrFail($id);
        $validated = $this->validated($request, false);

        if ($request->hasFile('image')) {
            $old = $card->image;
            $validated['image'] = CustomImage::storeFile($request->file('image'), 'tag')['name'];
            if ($old && $old !== $validated['image']) {
                Storage::disk('public')->delete('tag/'.$old);
            }
        } else {
            unset($validated['image']);
        }

        $card->update($validated);

        return redirect()->route('explore-cards.index')->with('status', 'Kartu berhasil diperbarui');
    }

    public function toggle($id)
    {
        $card = Tag::findOrFail($id);
        $card->update(['status' => ! $card->status]);

        return back()->with('status', $card->status ? 'Kartu ditampilkan di beranda' : 'Kartu disembunyikan dari beranda');
    }

    public function destroy($id)
    {
        $card = Tag::findOrFail($id);
        $used = CategoryPackage::where('tag_id', $card->id)->count();
        if ($used > 0) {
            return back()->with('error', 'Tag "'.$card->name.'" masih dipakai '.$used.' paket wisata. Sembunyikan saja dari beranda.');
        }

        if ($card->image) {
            Storage::disk('public')->delete('tag/'.$card->image);
        }
        $card->delete();

        return redirect()->route('explore-cards.index')->with('status', 'Kartu dihapus');
    }

    private function validated(Request $request, bool $creating): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'name_id' => 'nullable|string|max:191',
            'desc' => 'nullable|string|max:300',
            'desc_id' => 'nullable|string|max:300',
            'image' => [$creating ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'url' => ['nullable', 'string', 'max:255', 'not_regex:/^file:/i'],
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
        ], [], [
            'name' => 'Judul (EN)',
            'name_id' => 'Judul (ID)',
            'desc' => 'Deskripsi (EN)',
            'desc_id' => 'Deskripsi (ID)',
            'image' => 'Gambar',
            'url' => 'Link tujuan',
        ]);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['status'] = $request->boolean('status', true);
        $validated['desc'] = $validated['desc'] ?? '';

        return $validated;
    }
}
