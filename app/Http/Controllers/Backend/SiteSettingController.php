<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\Site;
use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $settings = SiteSetting::orderBy('id')->get();

        return view('backend.site_settings.index')->with(compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string|max:500',
            'settings.facebook' => 'nullable|url|max:500',
            'settings.instagram' => 'nullable|url|max:500',
            'settings.youtube' => 'nullable|url|max:500',
            'settings.linkedin' => 'nullable|url|max:500',
            'settings.tourismtrends' => 'nullable|url|max:500',
        ], [
            'settings.*.url' => 'Isi dengan URL lengkap, diawali https://',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            SiteSetting::where('key', $key)->update(['value' => $value]);
        }

        Site::flush();

        return redirect(route('site-settings.index'))->with('status', 'Pengaturan website berhasil diperbarui');
    }
}
