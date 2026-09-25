<?php

namespace App\Http\Controllers\Front;

use App\Helpers\CustomImage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Guest\VillageSubmissionRequest;
use App\Models\VillageSubmission;
use App\Support\Seo;
use Illuminate\Support\Str;

class VillageSubmissionController extends Controller
{
    public function create()
    {
        $data['seo'] = Seo::make()
            ->title('Daftarkan Desa Wisata')
            ->description('Daftarkan desa wisata Anda ke jaringan GODEVI. Isi formulir pengajuan desa, tim kami akan memverifikasi dan menghubungi Anda.')
            ->canonical('/daftar-desa')
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', 'Daftar Desa' => '/daftar-desa'])
            ->toArray();

        return view('customer.village-submission.create', $data);
    }

    public function store(VillageSubmissionRequest $request)
    {
        $payload = $request->validated();
        $payload['uuid'] = (string) Str::uuid();
        $payload['status'] = 'pending';

        if ($request->hasFile('attachment')) {
            $upload = CustomImage::storeImage($request->file('attachment'), 'village-submissions');
            $payload['attachment'] = $upload['name'];
        }

        $submission = VillageSubmission::create($payload);

        return redirect()->route('village-submission.success', $submission->uuid)
            ->with('status', 'Pengajuan desa berhasil dikirim. Tim GODEVI akan memverifikasi.');
    }

    public function success(string $uuid)
    {
        $submission = VillageSubmission::where('uuid', $uuid)->firstOrFail();

        $data['submission'] = $submission;
        $data['seo'] = Seo::make()->title('Pengajuan Desa Terkirim')->noindex()->toArray();

        return view('customer.village-submission.success', $data);
    }
}
