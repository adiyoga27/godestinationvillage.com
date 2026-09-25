@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="Booking Paket — Tanpa Login"
    subtitle="Alur 2 — {{ $packages->name }}. Pembayaran via Midtrans Snap."
    image="assets/customer/img/page-title-area/account.jpg"
    :crumbs="['Home' => '/', 'Tour Packages' => '/tour-packages', 'Guest Booking' => '']"
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

        <form action="{{ route('guest-booking.package.store') }}" method="post" class="space-y-8">
            @csrf
            <input type="hidden" name="idtour" value="{{ $packages->id }}">

            <div class="card p-8 sm:p-10">
                <h2 class="font-display text-xl font-bold text-ink-950">Paket: {{ $packages->name }}</h2>
                <p class="mt-2 text-sm text-ink-500">Harga/pax: Rp {{ number_format($packages->disc > 0 ? $packages->disc : $packages->price, 0, ',', '.') }}</p>
            </div>

            <div class="card p-8 sm:p-10">
                <h2 class="font-display text-xl font-bold text-ink-950">Data Tamu</h2>
                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    <label class="block"><span class="label-gd">Nama Lengkap *</span><input type="text" name="customername" value="{{ old('customername') }}" required class="input-gd"></label>
                    <label class="block"><span class="label-gd">Email *</span><input type="email" name="email" value="{{ old('email') }}" required class="input-gd"></label>
                    <label class="block"><span class="label-gd">Alamat *</span><input type="text" name="address" value="{{ old('address') }}" required class="input-gd"></label>
                    <label class="block"><span class="label-gd">Telepon *</span><input type="text" name="phone" value="{{ old('phone') }}" required class="input-gd"></label>
                    <label class="block"><span class="label-gd">Jumlah Pax *</span><input type="number" name="pax" min="1" value="{{ old('pax', 2) }}" required class="input-gd"></label>
                    <label class="block"><span class="label-gd">Tanggal Kunjungan</span><input type="date" name="checkin_date" value="{{ old('checkin_date') }}" class="input-gd"></label>
                    <label class="block"><span class="label-gd">Lokasi Jemput</span><input type="text" name="pickup" value="{{ old('pickup') }}" class="input-gd"></label>
                    <label class="block"><span class="label-gd">Nama Hotel/Villa</span><input type="text" name="pickupname" value="{{ old('pickupname') }}" class="input-gd"></label>
                    <label class="block sm:col-span-2"><span class="label-gd">Catatan Khusus</span><textarea name="special_note" rows="3" class="input-gd resize-none">{{ old('special_note') }}</textarea></label>
                </div>
            </div>

            <div class="card flex flex-col gap-4 p-8 sm:flex-row sm:items-center sm:justify-between sm:p-10">
                <p class="text-sm text-ink-500">Lanjut ke pembayaran Midtrans (Snap embed). Bisa bayar tanpa akun.</p>
                <button type="submit" class="btn btn-primary shrink-0 !px-10 !py-4">Lanjut ke Pembayaran</button>
            </div>
        </form>
    </div>
</section>
@endsection
