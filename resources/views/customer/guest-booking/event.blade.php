@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="Booking Event — Tanpa Login"
    subtitle="Alur 3 — {{ $packages->name }}. Gratis langsung terbit, berbayar via Midtrans."
    image="assets/customer/img/page-title-area/account.jpg"
    :crumbs="['Home' => '/', 'Events' => '/events', 'Guest Booking' => '']"
/>

<section class="section-pad bg-cream-50">
    <div class="container-gd max-w-4xl">
        @if (session('error'))
            <div class="card mb-6 border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="card mb-6 border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('guest-booking.event.store') }}" method="post" class="space-y-8">
            @csrf
            <input type="hidden" name="idevent" value="{{ $packages->id }}">

            <div class="card p-8 sm:p-10">
                <h2 class="font-display text-xl font-bold text-ink-950">Event: {{ $packages->name }}</h2>
                <p class="mt-2 text-sm text-ink-500">
                    @if ($packages->is_free)
                        Tiket gratis — langsung terkonfirmasi tanpa pembayaran.
                    @elseif ($packages->is_paywish)
                        Donasi sukarela — nominal per pax diisi manual, bayar via Midtrans.
                    @else
                        Harga/pax: Rp {{ number_format($packages->disc > 0 ? $packages->price - $packages->disc : $packages->price, 0, ',', '.') }} — bayar via Midtrans.
                    @endif
                </p>
            </div>

            <div class="card p-8 sm:p-10">
                <h2 class="font-display text-xl font-bold text-ink-950">Data Peserta</h2>
                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    <label class="block"><span class="label-gd">Nama Lengkap *</span><input type="text" name="customername" value="{{ old('customername') }}" required class="input-gd"></label>
                    <label class="block"><span class="label-gd">Email *</span><input type="email" name="email" value="{{ old('email') }}" required class="input-gd"></label>
                    <label class="block"><span class="label-gd">Alamat *</span><input type="text" name="address" value="{{ old('address') }}" required class="input-gd"></label>
                    <label class="block"><span class="label-gd">Telepon *</span><input type="text" name="phone" value="{{ old('phone') }}" required class="input-gd"></label>
                    <label class="block"><span class="label-gd">Jumlah Pax *</span><input type="number" name="pax" min="1" value="{{ old('pax', 1) }}" required class="input-gd"></label>
                    @if ($packages->is_paywish)
                        <label class="block"><span class="label-gd">Donasi / Pax (Rp) *</span><input type="number" name="price" min="0" value="{{ old('price') }}" required class="input-gd"></label>
                    @endif
                    <label class="block sm:col-span-2"><span class="label-gd">Catatan</span><textarea name="special_note" rows="3" class="input-gd resize-none">{{ old('special_note') }}</textarea></label>
                </div>
            </div>

            <div class="card flex flex-col gap-4 p-8 sm:flex-row sm:items-center sm:justify-between sm:p-10">
                <p class="text-sm text-ink-500">Tiket gratis langsung sukses; berbayar lanjut ke Midtrans Snap.</p>
                <button type="submit" class="btn btn-primary shrink-0 !px-10 !py-4">Buat Booking</button>
            </div>
        </form>
    </div>
</section>
@endsection
