<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookletController extends Controller
{
    const BOOKLET_PATH = 'documents/GODEVI-Booklet.pdf';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $disk = Storage::disk('public');
        $file = $disk->exists(self::BOOKLET_PATH)
            ? [
                'name' => basename(self::BOOKLET_PATH),
                'size' => $this->formatBytes($disk->size(self::BOOKLET_PATH)),
                'updated_at' => date('Y-m-d H:i', $disk->lastModified(self::BOOKLET_PATH)),
                'url' => asset('storage/' . self::BOOKLET_PATH),
            ]
            : null;

        return view('backend.booklet.form')->with(compact('file'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:30720',
        ]);

        $file = $request->file('pdf');

        if ($file->getSize() > 30720 * 1024) {
            return back()->withErrors(['pdf' => 'Ukuran file maksimal 30 MB.']);
        }

        $file->storeAs('documents', 'GODEVI-Booklet.pdf', 'public');

        return redirect(route('booklet.index'))->with('status', 'Booklet PDF berhasil diperbarui');
    }

    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 1) . ' ' . $units[$i];
    }
}