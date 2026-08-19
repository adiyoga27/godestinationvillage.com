@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="{{ __('Our Team') }}"
    subtitle="{{ __('Meet the passionate people behind GODEVI — dedicated to uplifting communities through responsible tourism.') }}"
    image="assets/customer/img/page-title-area/our-team-timenile.jpg"
    :crumbs="[__('Home') => '/', __('Our Team') => '']"
/>

<section class="section-pad">
    <div class="container-gd">
        <x-partials.team-grid :members="$ours" folder="ourteam" />
    </div>
</section>
@endsection