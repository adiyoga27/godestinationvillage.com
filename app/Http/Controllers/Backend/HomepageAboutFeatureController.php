<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\CustomImage;
use App\Helpers\Homepage;
use App\Http\Controllers\Controller;
use App\Models\HomepageAboutFeature;
use Illuminate\Http\Request;

class HomepageAboutFeatureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $features = HomepageAboutFeature::orderBy('sort_order')->orderBy('id')->get();

        return view('backend.homepage_about_features.index')->with(compact('features'));
    }

    public function create()
    {
        return view('backend.homepage_about_features.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:191',
            'title_id' => 'nullable|max:191',
            'desc' => 'nullable',
            'desc_id' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $upload = CustomImage::storeFile($request->file('image'), 'homepage-about-features');
            $validated['image'] = $upload['name'];
        }
        $validated['is_active'] = $request->boolean('is_active', true);

        HomepageAboutFeature::create($validated);
        Homepage::flush();

        return redirect(route('homepage-about-features.index'))->with('status', 'Kartu fitur berhasil ditambahkan');
    }

    public function edit($id)
    {
        $feature = HomepageAboutFeature::findOrFail($id);

        return view('backend.homepage_about_features.edit')->with(compact('feature'));
    }

    public function update(Request $request, $id)
    {
        $feature = HomepageAboutFeature::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|max:191',
            'title_id' => 'nullable|max:191',
            'desc' => 'nullable',
            'desc_id' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $upload = CustomImage::storeFile($request->file('image'), 'homepage-about-features');
            $validated['image'] = $upload['name'];
        } else {
            unset($validated['image']);
        }
        if ($request->boolean('remove_image')) {
            $validated['image'] = null;
        }

        $validated['is_active'] = $request->boolean('is_active');

        $feature->update($validated);
        Homepage::flush();

        return redirect(route('homepage-about-features.index'))->with('status', 'Kartu fitur berhasil diperbarui');
    }

    public function destroy($id)
    {
        $feature = HomepageAboutFeature::findOrFail($id);
        $feature->delete();
        Homepage::flush();

        return redirect(route('homepage-about-features.index'))->with('status', 'Kartu fitur berhasil dihapus');
    }

    public function toggle($id)
    {
        $feature = HomepageAboutFeature::findOrFail($id);
        $feature->update(['is_active' => ! $feature->is_active]);
        Homepage::flush();

        return redirect(route('homepage-about-features.index'))->with('status', 'Kartu fitur berhasil diperbarui');
    }
}
