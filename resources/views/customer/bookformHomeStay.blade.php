@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="{{ __('Reservation Form') }}"
    subtitle="{{ __('Complete your reservation for :name', ['name' => $packages->name]) }}"
    image="assets/customer/img/page-title-area/homestay.jpg"
    :crumbs="[__('Home') => '/', __('Homestay') => '/homestay', __('Reservation') => '']"
/>

<section class="section-pad bg-cream-50">
    <div class="container-gd max-w-4xl">
        <form action="{{ url('bookingHomeStay/sendHomeStay') }}" method="post" class="space-y-8">
            @csrf
            <input type="hidden" name="idhomestay" value="{{ $packages->id }}">

            <div class="card p-8 sm:p-10">
                <h2 class="font-display text-xl font-bold text-ink-950">{{ __('Homestay Information') }}</h2>
                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    <div>
                        <span class="label-gd">{{ __('Homestay Name') }}</span>
                        <input type="text" name="homestayname" value="{{ $packages->name }}" readonly class="input-gd bg-cream-50 text-ink-900">
                    </div>
                    <div>
                        <span class="label-gd">{{ __('Type') }}</span>
                        <input type="text" name="type" value="{{ $packages->category->name }}" readonly class="input-gd bg-cream-50 text-ink-900">
                    </div>
                </div>
            </div>

            <div class="card p-8 sm:p-10">
                <h2 class="font-display text-xl font-bold text-ink-950">{{ __('Customer Information') }}</h2>
                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    <label class="block">
                        <span class="label-gd">{{ __('Customer Name') }}</span>
                        <input type="text" name="customername" placeholder="{{ __('Input your name') }}" value="@isset($user){{ $user->name }}@endisset" required class="input-gd">
                    </label>
                    <label class="block">
                        <span class="label-gd">{{ __('Email') }}</span>
                        <input type="email" name="email" placeholder="{{ __('Input your email') }}" value="@isset($user){{ $user->email }}@endisset" required class="input-gd">
                    </label>
                    <label class="block">
                        <span class="label-gd">{{ __('Address') }}</span>
                        <input type="text" name="address" placeholder="{{ __('Input your address') }}" value="@isset($user){{ $user->address }}@endisset" required class="input-gd">
                    </label>
                    <label class="block">
                        <span class="label-gd">{{ __('Phone') }}</span>
                        <input type="text" name="phone" placeholder="{{ __('Input your phone') }}" value="@isset($user){{ $user->phone }}@endisset" required class="input-gd">
                    </label>
                </div>
            </div>

            <div class="card p-8 sm:p-10">
                <h2 class="font-display text-xl font-bold text-ink-950">{{ __('Reservation Information') }}</h2>
                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    <label class="block">
                        <span class="label-gd">{{ __('Pax') }}</span>
                        <input type="number" name="pax" min="1" value="1" class="input-gd pax">
                    </label>
                    <label class="block">
                        <span class="label-gd">{{ __('Price / Pax') }}</span>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-brand-600">Rp</span>
                            <input type="text" name="price" value="{{ $packages->price }}" readonly class="input-gd price bg-cream-50 pl-11" style="color:#1a1a26 !important; -webkit-text-fill-color:#1a1a26;">
                        </div>
                        <input type="hidden" name="totalprice" class="totalprice" value="{{ $packages->price }}">
                    </label>
                    <label class="block sm:col-span-2">
                        <span class="label-gd">{{ __('Special Note') }}</span>
                        <textarea name="special_note" rows="4" placeholder="{{ __('Input your note transaction') }}" class="input-gd resize-none"></textarea>
                    </label>
                </div>
            </div>

            <div class="card flex flex-col gap-6 p-8 sm:flex-row sm:items-center sm:justify-between sm:p-10">
                <div>
                    <p class="text-sm text-ink-500">{{ __('Total') }}</p>
                    <p class="total mt-1 font-display text-3xl font-bold text-brand-600">Rp {{ number_format($packages->price, 0, ',', '.') }}</p>
                    <p class="mt-2 text-xs text-ink-400">{{ __('* Please check your form — the order cannot be changed.') }}</p>
                </div>
                <button type="submit" class="btn btn-primary shrink-0 !px-10 !py-4">{{ __('Book Now') }}</button>
            </div>
        </form>
    </div>
</section>
@endsection

@section('js')
<script>
    (function () {
        var pax = document.querySelector('.pax');
        var price = document.querySelector('.price');
        var totalInput = document.querySelector('.totalprice');
        var totalEl = document.querySelector('.total');
        function updateTotal() {
            var v = parseInt(pax.value, 10);
            var min = parseInt(pax.getAttribute('min'), 10);
            if (v < min) { v = min; pax.value = min; }
            var total = v * parseInt(price.value || '0', 10);
            if (totalInput) totalInput.value = total;
            if (totalEl) totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
        }
        if (pax && price) {
            pax.addEventListener('change', updateTotal);
            pax.addEventListener('keyup', updateTotal);
        }
    })();
</script>
@endsection