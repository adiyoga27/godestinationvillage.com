@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="Delete Account"
    subtitle="Learn how to request the deletion of your GODEVI account."
    image="assets/customer/img/page-title-area/privacy.jpg"
    :crumbs="['Home' => '/', 'Delete Account' => '']"
/>

<section class="section-pad">
    <div class="container-gd max-w-3xl">
        <div class="card p-8 sm:p-12">
            <h1 class="font-display text-2xl font-bold text-ink-950">Penghapusan Akun</h1>
            <p class="mt-4 leading-relaxed text-ink-600">Jika Anda ingin melakukan penghapusan akun (delete account), silakan mengirimkan email ke alamat berikut:</p>
            <p class="mt-6">
                <a href="mailto:admin@godestinationvillage.com" class="inline-flex items-center gap-2 rounded-2xl bg-brand-50 px-5 py-3 font-bold text-brand-700 transition hover:bg-brand-600 hover:text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                    admin@godestinationvillage.com
                </a>
            </p>
            <p class="mt-6 leading-relaxed text-ink-600">Mohon sertakan <strong class="text-ink-900">nama akun</strong> pada email tersebut. Tim admin kami akan memproses permintaan penghapusan akun Anda dalam waktu maksimal <strong class="text-ink-900">24 jam</strong>.</p>
            <p class="mt-6 leading-relaxed text-ink-600">Terima kasih atas kepercayaan Anda menggunakan layanan GoDestinationVillage.</p>
        </div>
    </div>
</section>
@endsection