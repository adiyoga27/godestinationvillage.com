@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="Pengajuan Terkirim"
    subtitle="Terima kasih — data desa Anda sudah masuk antrean verifikasi tim."
    image="assets/customer/img/page-title-area/account.jpg"
    :crumbs="['Home' => '/', 'Daftar Desa' => '/daftar-desa', 'Berhasil' => '']"
/>

<section class="section-pad bg-cream-50">
    <div class="container-gd max-w-2xl">
        <div class="card p-10 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-2xl">✓</div>
            <h2 class="mt-6 font-display text-2xl font-bold text-ink-950">Pengajuan {{ $submission->village_name }} diterima</h2>
            <p class="mt-3 text-ink-600">Status: <strong>{{ ucfirst($submission->status) }}</strong><br>Kode pelacakan: <code>{{ $submission->uuid }}</code></p>
            <p class="mt-3 text-sm text-ink-500">Tim GODEVI akan menghubungi {{ $submission->email }} / {{ $submission->phone }} setelah verifikasi.</p>
            <div class="mt-8 flex justify-center gap-3">
                <a href="{{ url('/') }}" class="btn btn-secondary">Kembali ke Beranda</a>
                <a href="{{ url('/village') }}" class="btn btn-primary">Jelajahi Desa</a>
            </div>
        </div>
    </div>
</section>
@endsection
