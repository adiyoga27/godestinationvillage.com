@extends('customer/layout')

@section('content')

<x-partials.page-hero
    page="daftar-desa"
    title="Daftarkan Desa Wisata"
    subtitle="Alur 1 — Pengajuan desa wisata baru oleh komunitas. Tanpa pembayaran, diverifikasi tim GODEVI."
    image="assets/customer/img/page-title-area/account.jpg"
    :crumbs="['Home' => '/', 'Daftar Desa' => '']"
/>

<section class="section-pad bg-cream-50">
    <div class="container-gd max-w-4xl">
        @if ($errors->any())
            <div class="card mb-6 border-red-200 bg-red-50 p-6 text-sm text-red-700">
                <p class="font-bold">Periksa kembali isian Anda:</p>
                <ul class="mt-2 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('village-submission.store') }}" method="post" enctype="multipart/form-data" class="space-y-8">
            @csrf
            <div class="card p-8 sm:p-10">
                <h2 class="font-display text-xl font-bold text-ink-950">Informasi Desa</h2>
                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    <label class="block">
                        <span class="label-gd">Nama Desa Wisata *</span>
                        <input type="text" name="village_name" value="{{ old('village_name') }}" required class="input-gd" placeholder="cth: Desa Penglipuran">
                    </label>
                    <label class="block">
                        <span class="label-gd">Kabupaten / Kota</span>
                        <input type="text" name="regency" value="{{ old('regency') }}" class="input-gd" placeholder="cth: Bangli">
                    </label>
                    <label class="block sm:col-span-2">
                        <span class="label-gd">Alamat Lengkap *</span>
                        <textarea name="address" rows="3" required class="input-gd resize-none" placeholder="Alamat desa">{{ old('address') }}</textarea>
                    </label>
                    <label class="block sm:col-span-2">
                        <span class="label-gd">Deskripsi Singkat Desa</span>
                        <textarea name="description" rows="4" class="input-gd resize-none" placeholder="Ceritakan tentang desa Anda">{{ old('description') }}</textarea>
                    </label>
                    <label class="block sm:col-span-2">
                        <span class="label-gd">Potensi Wisata</span>
                        <textarea name="tourism_potential" rows="4" class="input-gd resize-none" placeholder="cth: air terjun, tenun, kuliner khas">{{ old('tourism_potential') }}</textarea>
                    </label>
                </div>
            </div>

            <div class="card p-8 sm:p-10">
                <h2 class="font-display text-xl font-bold text-ink-950">Kontak Pengaju</h2>
                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    <label class="block">
                        <span class="label-gd">Nama Lengkap *</span>
                        <input type="text" name="contact_name" value="{{ old('contact_name') }}" required class="input-gd">
                    </label>
                    <label class="block">
                        <span class="label-gd">No. Telepon / WA *</span>
                        <input type="text" name="phone" value="{{ old('phone') }}" required class="input-gd">
                    </label>
                    <label class="block">
                        <span class="label-gd">Email *</span>
                        <input type="email" name="email" value="{{ old('email') }}" required class="input-gd">
                    </label>
                    <label class="block">
                        <span class="label-gd">Dokumen Pendukung (JPG/PNG/PDF, maks 4MB)</span>
                        <input type="file" name="attachment" class="input-gd">
                    </label>
                </div>
            </div>

            <div class="card flex flex-col gap-4 p-8 sm:flex-row sm:items-center sm:justify-between sm:p-10">
                <p class="text-sm text-ink-500">Pengajuan masuk status <strong>pending</strong> dan diverifikasi oleh Dashboard Tim di admin.</p>
                <button type="submit" class="btn btn-primary shrink-0 !px-10 !py-4">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</section>
@endsection
