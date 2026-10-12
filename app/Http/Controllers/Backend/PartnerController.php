<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\CustomImage;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $partners = Partner::orderBy('sort_order')->orderBy('id')->get();

        return view('backend.partners.index')->with(compact('partners'));
    }

    public function create()
    {
        return view('backend.partners.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validatePartner($request, true);

        $upload = CustomImage::storeFile($request->file('logo'), 'partners');
        $validated['logo'] = $upload['name'];
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? (int) Partner::max('sort_order') + 1;

        Partner::create($validated);

        return redirect(route('partners.index'))->with('status', 'Partner berhasil ditambahkan');
    }

    public function show($id)
    {
        return redirect(route('partners.edit', $id));
    }

    public function edit($id)
    {
        $partner = Partner::findOrFail($id);

        return view('backend.partners.edit')->with(compact('partner'));
    }

    public function update(Request $request, $id)
    {
        $partner = Partner::findOrFail($id);
        $validated = $this->validatePartner($request, false);

        if ($request->hasFile('logo')) {
            $upload = CustomImage::storeFile($request->file('logo'), 'partners');
            $validated['logo'] = $upload['name'];
        } else {
            unset($validated['logo']);
        }
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $partner->update($validated);

        return redirect(route('partners.index'))->with('status', 'Partner berhasil diperbarui');
    }

    public function destroy($id)
    {
        Partner::findOrFail($id)->delete();

        return redirect(route('partners.index'))->with('status', 'Partner berhasil dihapus');
    }

    public function toggle($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->update(['is_active' => ! $partner->is_active]);

        return redirect(route('partners.index'))->with('status', 'Partner berhasil diperbarui');
    }

    protected function validatePartner(Request $request, bool $creating): array
    {
        return $request->validate([
            'name' => 'required|max:191',
            'name_id' => 'nullable|max:191',
            'desc' => 'nullable|max:1000',
            'desc_id' => 'nullable|max:1000',
            'logo' => ($creating ? 'required' : 'nullable').'|image|mimes:jpg,jpeg,png,webp|max:5120',
            'url' => ['nullable', 'max:191', 'regex:/^https?:\/\//i'],
            'sort_order' => 'nullable|integer',
        ], [
            'url.regex' => 'Link website harus diawali http:// atau https://',
        ]);
    }
}
