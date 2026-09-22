<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\CustomImage;
use App\Helpers\Homepage;
use App\Http\Controllers\Controller;
use App\Models\HomepageService;
use Illuminate\Http\Request;

class HomepageServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $services = HomepageService::orderBy('sort_order')->orderBy('id')->get();

        return view('backend.homepage_services.index')->with(compact('services'));
    }

    public function create()
    {
        return view('backend.homepage_services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:191',
            'title_id' => 'nullable|max:191',
            'image' => 'required|image|max:10240',
            'url' => 'nullable|max:191',
            'sort_order' => 'nullable|integer',
        ]);

        $upload = CustomImage::storeFile($request->file('image'), 'homepage-services');
        $validated['image'] = $upload['name'];
        $validated['is_active'] = $request->boolean('is_active', true);

        HomepageService::create($validated);
        Homepage::flush();

        return redirect(route('homepage-services.index'))->with('status', 'Item service berhasil ditambahkan');
    }

    public function edit($id)
    {
        $service = HomepageService::findOrFail($id);

        return view('backend.homepage_services.edit')->with(compact('service'));
    }

    public function update(Request $request, $id)
    {
        $service = HomepageService::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|max:191',
            'title_id' => 'nullable|max:191',
            'image' => 'nullable|image|max:10240',
            'url' => 'nullable|max:191',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $upload = CustomImage::storeFile($request->file('image'), 'homepage-services');
            $validated['image'] = $upload['name'];
        } else {
            unset($validated['image']);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $service->update($validated);
        Homepage::flush();

        return redirect(route('homepage-services.index'))->with('status', 'Item service berhasil diperbarui');
    }

    public function destroy($id)
    {
        $service = HomepageService::findOrFail($id);
        $service->delete();
        Homepage::flush();

        return redirect(route('homepage-services.index'))->with('status', 'Item service berhasil dihapus');
    }

    public function toggle($id)
    {
        $service = HomepageService::findOrFail($id);
        $service->update(['is_active' => ! $service->is_active]);
        Homepage::flush();

        return redirect(route('homepage-services.index'))->with('status', 'Item service berhasil diperbarui');
    }
}
