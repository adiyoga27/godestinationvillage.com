@php
    $seo = $seo ?? \App\Support\Seo::make()->toArray();
    $schema = $seo['schema'] ?? [];
    $logo = url('assets/customer/img/logo.png');
    $image = $seo['image'] ?? $logo;
    $siteName = 'GODEVI - Authentic Village Experiences';
@endphp

<title>{{ $seo['title'] ?? $siteName }}</title>
<meta name="description" content="{{ $seo['description'] ?? '' }}">
@if (!empty($seo['keywords']))
    <meta name="keywords" content="{{ implode(', ', $seo['keywords']) }}">
@endif
<link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">
<meta name="robots" content="{{ $seo['robots'] ?? 'index,follow' }}">
<meta name="theme-color" content="#d81c25">

<!-- Open Graph -->
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:type" content="{{ $seo['type'] ?? 'website' }}">
<meta property="og:title" content="{{ $seo['title'] ?? $siteName }}">
<meta property="og:description" content="{{ $seo['description'] ?? '' }}">
<meta property="og:url" content="{{ $seo['canonical'] ?? url()->current() }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:locale" content="{{ app()->getLocale() === 'id' ? 'id_ID' : 'en_US' }}">
<meta property="og:locale:alternate" content="{{ app()->getLocale() === 'id' ? 'en_US' : 'id_ID' }}">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seo['title'] ?? $siteName }}">
<meta name="twitter:description" content="{{ $seo['description'] ?? '' }}">
<meta name="twitter:image" content="{{ $image }}">

@if (!empty($seo['schema']))
    @foreach ($seo['schema'] as $schemaBlock)
        <script type="application/ld+json">{!! json_encode($schemaBlock, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endforeach
@endif
