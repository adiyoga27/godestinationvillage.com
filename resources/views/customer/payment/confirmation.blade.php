@extends('customer/layout')

@section('content')

@php
    $seo = \App\Support\Seo::make()->title('Confirm Payment — GODEVI')->noindex()->toArray();
@endphp

<x-partials.page-hero
    title="Confirm Payment"
    subtitle="Complete your bank transfer confirmation so our team can verify and approve your booking."
    image="assets/customer/img/page-title-area/privacy.jpg"
    :crumbs="['Home' => '/', 'Confirm Payment' => '']"
/>

<section class="section-pad bg-cream-50">
    <div class="container-gd mx-auto max-w-2xl">
        <div class="card overflow-hidden">
            <div class="border-b border-ink-100 bg-ink-950 px-8 py-6">
                <p class="text-xs font-bold uppercase tracking-wider text-ink-400">Order Code</p>
                <p class="mt-1 font-display text-xl font-bold text-white">{{ $order->code ?? '-' }}</p>
            </div>
            <form action="{{ url('payment/pay/confirm-payment') }}" method="POST" enctype="multipart/form-data" class="space-y-6 p-8 sm:p-10">
                @csrf
                <input type="hidden" name="idtrx" value="{{ $order->id }}">

                <div>
                    <label for="bank" class="mb-2 block text-sm font-bold text-ink-800">Your Bank Account</label>
                    <select name="bank" id="bank" class="w-full rounded-xl border border-ink-200 bg-white px-4 py-3 text-sm text-ink-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        @foreach ($bank as $banks)
                            <option value="{{ $banks->bank_name }}">{{ $banks->bank_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="name" class="mb-2 block text-sm font-bold text-ink-800">Name Account</label>
                    <input type="text" name="name" id="name" placeholder="Enter your bank account name"
                        class="w-full rounded-xl border border-ink-200 bg-white px-4 py-3 text-sm text-ink-800 placeholder-ink-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                </div>

                <div>
                    <label for="date" class="mb-2 block text-sm font-bold text-ink-800">Date of Transfer</label>
                    <input type="text" name="date" id="date" placeholder="22/04/2019"
                        class="w-full rounded-xl border border-ink-200 bg-white px-4 py-3 text-sm text-ink-800 placeholder-ink-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                </div>

                <div>
                    <label for="message" class="mb-2 block text-sm font-bold text-ink-800">Message</label>
                    <input type="text" name="message" id="message" placeholder="Message"
                        class="w-full rounded-xl border border-ink-200 bg-white px-4 py-3 text-sm text-ink-800 placeholder-ink-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                </div>

                <div>
                    <label for="bukti" class="mb-2 block text-sm font-bold text-ink-800">Evidence of Transfer</label>
                    <input type="file" name="bukti" id="bukti"
                        class="w-full rounded-xl border border-dashed border-ink-300 bg-cream-50 px-4 py-6 text-sm text-ink-600 focus:outline-none">
                </div>

                <button type="submit" class="btn btn-primary w-full !py-4">Confirm Now</button>
            </form>
        </div>
    </div>
</section>

@endsection

@section('js')
<script src="{{ url('assets/customer/frontdata/js/jquery.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script>
    $(function() {
        $("#date").datepicker({ dateFormat: 'dd/mm/yy' });
    });
</script>
@endsection