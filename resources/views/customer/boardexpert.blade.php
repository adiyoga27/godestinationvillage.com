@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="{{ __('Board of Experts') }}"
    subtitle="{{ __('The advisors guiding GODEVI in sustainable tourism, community development and destination management.') }}"
    image="assets/customer/img/page-title-area/team.jpg"
    :crumbs="[__('Home') => '/', __('Board of Experts') => '']"
/>

<section class="section-pad">
    <div class="container-gd">
        <x-partials.team-grid :members="$boards" folder="board" />
    </div>
</section>
@endsection