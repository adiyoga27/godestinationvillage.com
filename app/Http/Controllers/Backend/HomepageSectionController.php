<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\CustomImage;
use App\Helpers\Homepage;
use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use Illuminate\Http\Request;

class HomepageSectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sections = HomepageSection::orderBy('sort_order')->get();

        return view('backend.homepage_sections.index')->with(compact('sections'));
    }

    public function edit($id)
    {
        $section = HomepageSection::findOrFail($id);

        return view('backend.homepage_sections.edit')->with(compact('section'));
    }

    public function update(Request $request, $id)
    {
        $section = HomepageSection::findOrFail($id);

        $validated = $request->validate([
            'eyebrow' => 'nullable|max:191',
            'eyebrow_id' => 'nullable|max:191',
            'title' => 'nullable|max:191',
            'title_id' => 'nullable|max:191',
            'subtitle' => 'nullable',
            'subtitle_id' => 'nullable',
            'image' => 'nullable|image|max:10240',
            'button_label' => 'nullable|max:191',
            'button_label_id' => 'nullable|max:191',
            'button_url' => 'nullable|max:191',
            'button2_label' => 'nullable|max:191',
            'button2_label_id' => 'nullable|max:191',
            'button2_url' => 'nullable|max:191',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $upload = CustomImage::storeFile($request->file('image'), 'homepage-sections');
            $validated['image'] = $upload['name'];
        } else {
            unset($validated['image']);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $section->update($validated);
        Homepage::flush();

        return redirect(route('homepage-sections.index'))->with('status', 'Section "'.$section->name.'" berhasil diperbarui');
    }

    public function toggle($id)
    {
        $section = HomepageSection::findOrFail($id);
        $section->update(['is_active' => ! $section->is_active]);
        Homepage::flush();

        return redirect(route('homepage-sections.index'))->with(
            'status',
            'Section "'.$section->name.'" sekarang '.($section->is_active ? 'TAMPIL' : 'DISEMBUNYIKAN').' di homepage'
        );
    }
}
