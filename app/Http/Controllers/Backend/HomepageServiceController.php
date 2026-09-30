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
            'desc' => 'nullable',
            'desc_id' => 'nullable',
            'image' => 'required|image|max:10240',
            'url' => 'nullable|max:191',
            'phone' => 'nullable|max:50',
            'whatsapp' => 'nullable|max:50',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',
            'buttons' => 'nullable|array',
            'buttons.*.label' => 'nullable|max:191',
            'buttons.*.label_id' => 'nullable|max:191',
            'buttons.*.url' => 'nullable|max:500',
            'sort_order' => 'nullable|integer',
        ]);

        $upload = CustomImage::storeFile($request->file('image'), 'homepage-services');
        $validated['image'] = $upload['name'];
        if ($request->hasFile('file')) {
            $fileUpload = CustomImage::storeFile($request->file('file'), 'homepage-services');
            $validated['file'] = $fileUpload['name'];
        }
        $validated['buttons'] = $this->cleanButtons($request->input('buttons'));
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
            'desc' => 'nullable',
            'desc_id' => 'nullable',
            'image' => 'nullable|image|max:10240',
            'url' => 'nullable|max:191',
            'phone' => 'nullable|max:50',
            'whatsapp' => 'nullable|max:50',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',
            'buttons' => 'nullable|array',
            'buttons.*.label' => 'nullable|max:191',
            'buttons.*.label_id' => 'nullable|max:191',
            'buttons.*.url' => 'nullable|max:500',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $upload = CustomImage::storeFile($request->file('image'), 'homepage-services');
            $validated['image'] = $upload['name'];
        } else {
            unset($validated['image']);
        }

        if ($request->hasFile('file')) {
            $fileUpload = CustomImage::storeFile($request->file('file'), 'homepage-services');
            $validated['file'] = $fileUpload['name'];
        } else {
            unset($validated['file']);
        }
        if ($request->boolean('remove_file') && ! $request->hasFile('file')) {
            $validated['file'] = null;
        }

        $validated['buttons'] = $this->cleanButtons($request->input('buttons'));
        $validated['is_active'] = $request->boolean('is_active');

        $service->update($validated);
        Homepage::flush();

        return redirect(route('homepage-services.index'))->with('status', 'Item service berhasil diperbarui');
    }

    /**
     * Buang baris tombol custom yang kosong total.
     */
    protected function cleanButtons($buttons): ?array
    {
        if (! is_array($buttons)) {
            return null;
        }

        $clean = [];
        foreach ($buttons as $b) {
            if (! is_array($b)) {
                continue;
            }
            $label = trim((string) ($b['label'] ?? ''));
            $labelId = trim((string) ($b['label_id'] ?? ''));
            $url = trim((string) ($b['url'] ?? ''));
            if ($label === '' && $labelId === '' && $url === '') {
                continue;
            }
            $clean[] = ['label' => $label, 'label_id' => $labelId, 'url' => $url];
        }

        return $clean ?: null;
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
