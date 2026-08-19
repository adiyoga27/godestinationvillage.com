@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="{{ __('Book Your Experience') }}"
    subtitle="{{ __('Complete your booking details for :name', ['name' => $packages->name]) }}"
    image="assets/customer/img/page-title-area/account.jpg"
    :crumbs="[__('Home') => '/', __('Tour Packages') => '/tour-packages', __('Booking') => '']"
/>

<section class="section-pad bg-cream-50">
    <div class="container-gd max-w-4xl">
        <form action="{{ url('booking/send') }}" method="post" class="space-y-8">
            @csrf
            <input type="hidden" name="idtour" value="{{ $packages->id }}">
            <input type="hidden" name="village_id" value="{{ $packages->village_id }}" required>

            <div class="card p-8 sm:p-10">
                <h2 class="font-display text-xl font-bold text-ink-950">{{ __('Tour Package Information') }}</h2>
                <div class="mt-6 grid gap-5 sm:grid-cols-3">
                    <div>
                        <span class="label-gd">{{ __('Tour Package Name') }}</span>
                        <input type="text" name="tourname" value="{{ $packages->name }}" readonly class="input-gd bg-cream-50 text-ink-900">
                    </div>
                    <div>
                        <span class="label-gd">{{ __('Type') }}</span>
                        <input type="text" name="type" value="{{ $packages->category->name }}" required readonly class="input-gd bg-cream-50 text-ink-900">
                    </div>
                    <div>
                        <span class="label-gd">{{ __('Village') }}</span>
                        <input type="text" name="village" value="{{ $packages->detailVillage->village_name }}" required readonly class="input-gd bg-cream-50 text-ink-900">
                    </div>
                </div>
            </div>

            <div class="card p-8 sm:p-10">
                <h2 class="font-display text-xl font-bold text-ink-950">{{ __('Customer Information') }}</h2>
                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    <input type="hidden" name="customerid" value="@isset($user){{ $user->id }}@endisset">
                    <label class="block">
                        <span class="label-gd">{{ __('Customer Name') }}</span>
                        <input type="text" name="customername" value="@isset($user){{ $user->name }}@endisset" required class="input-gd">
                    </label>
                    <label class="block">
                        <span class="label-gd">{{ __('Email') }}</span>
                        <input type="email" name="email" value="@isset($user){{ $user->email }}@endisset" required class="input-gd">
                    </label>
                    <label class="block">
                        <span class="label-gd">{{ __('Address') }}</span>
                        <input type="text" name="address" value="@isset($user){{ $user->address }}@endisset" required class="input-gd">
                    </label>
                    <label class="block">
                        <span class="label-gd">{{ __('Phone') }}</span>
                        <input type="text" name="phone" value="@isset($user){{ $user->phone }}@endisset" required class="input-gd">
                    </label>
                </div>
            </div>

            <div class="card p-8 sm:p-10">
                <h2 class="font-display text-xl font-bold text-ink-950">{{ __('Booking Information') }}</h2>
                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    <label class="block">
                        <span class="label-gd">{{ __('Pax') }}</span>
                        <input type="number" name="pax" min="2" value="2" id="pax" class="input-gd pax" required>
                    </label>
                    <label class="block">
                        <span class="label-gd">{{ __('Price / Pax') }} @if ($packages->disc > 0)<span class="text-brand-600">({{ __('Discount') }})</span>@endif</span>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-brand-600">Rp</span>
                            <input type="text" name="price" value="{{ $packages->disc > 0 ? $packages->disc : $packages->price }}" readonly class="input-gd price bg-cream-50 pl-11" style="color:#1a1a26 !important; -webkit-text-fill-color:#1a1a26;">
                        </div>
                        <input type="hidden" name="totalprice" class="totalprice" value="{{ $packages->disc > 0 ? $packages->disc : $packages->price }}" required>
                    </label>
                    @if ($packages->category->name != 'Virtual Tour')
                        <label class="block sm:col-span-2">
                            <span class="label-gd">{{ __('Date') }}</span>
                            <input type="datetime-local" name="checkin_date" required class="input-gd">
                        </label>
                        <label class="block sm:col-span-2">
                            <input type="checkbox" id="chkPassport" class="h-4 w-4 rounded border-ink-300 text-brand-600 focus:ring-brand-500">
                            <span class="ml-2 text-sm font-semibold text-ink-700">{{ __('Include Pickup Location?') }}</span>
                        </label>
                        <label class="block hidden" id="dvPassport">
                            <span class="label-gd">{{ __('Pick up location') }}</span>
                            <input type="text" name="pickup" value=" " placeholder="{{ __('Input your pick up location') }}" class="input-gd" required>
                        </label>
                        <label class="block hidden" id="dvPassport2">
                            <span class="label-gd">{{ __('Hotel / Villa / Guest House Name') }}</span>
                            <input type="text" name="pickupname" value=" " placeholder="{{ __('Input your place name') }}" class="input-gd" required>
                        </label>
                    @endif
                    <label class="block sm:col-span-2">
                        <span class="label-gd">{{ __('Special Note') }}</span>
                        <textarea name="special_note" rows="4" placeholder="{{ __('Input your note transaction') }}" class="input-gd resize-none"></textarea>
                    </label>
                </div>
            </div>

            <div class="card flex flex-col gap-6 p-8 sm:flex-row sm:items-center sm:justify-between sm:p-10">
                <div>
                    <p class="text-sm text-ink-500">{{ __('Total') }}</p>
                    <p class="total mt-1 font-display text-3xl font-bold text-brand-600">Rp {{ $packages->disc > 0 ? number_format($packages->disc, 0, ',', '.') : number_format($packages->price, 0, ',', '.') }}</p>
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
        var chk = document.getElementById('chkPassport');
        var box1 = document.getElementById('dvPassport');
        var box2 = document.getElementById('dvPassport2');
        if (chk && box1 && box2) {
            chk.addEventListener('change', function () {
                box1.classList.toggle('hidden', !chk.checked);
                box2.classList.toggle('hidden', !chk.checked);
            });
        }
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