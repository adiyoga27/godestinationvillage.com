@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="{{ __('The Founding') }}"
    subtitle="{{ __('The story of GODEVI — a socially pro-active business built to uplift village communities in Bali.') }}"
    image="assets/customer/img/page-title-area/founding-timenile.jpg"
    :crumbs="[__('Home') => '/', __('The Founding') => '']"
/>

<section class="section-pad">
    <div class="container-gd">
        <x-partials.team-grid :members="$foundings" folder="founding" />
    </div>
</section>
@endsection