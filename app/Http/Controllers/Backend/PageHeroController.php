<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\CustomImage;
use App\Helpers\PageHero;
use App\Http\Controllers\Controller;
use App\Models\PageHero as PageHeroModel;
use Illuminate\Http\Request;

class PageHeroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $heroes = PageHeroModel::orderBy('id')->get();

        return view('backend.page_heroes.index')->with(compact('heroes'));
    }

    public function edit($id)
    {
        $hero = PageHeroModel::findOrFail($id);

        return view('backend.page_heroes.edit')->with(compact('hero'));
    }

    public function update(Request $request, $id)
    {
        $hero = PageHeroModel::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|max:191',
            'title_id' => 'nullable|max:191',
            'subtitle' => 'nullable',
            'subtitle_id' => 'nullable',
            'image' => 'nullable|image|max:10240',
        ]);

        if ($request->hasFile('image')) {
            $upload = CustomImage::storeFile($request->file('image'), 'page-heroes');
            $validated['image'] = $upload['name'];
        } else {
            unset($validated['image']);
        }

        $hero->update($validated);
        PageHero::flush();

        return redirect(route('page-heroes.index'))->with('status', 'Hero halaman "'.$hero->name.'" berhasil diperbarui');
    }
}
