<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
    data-theme="theme-default" data-template="vertical-menu-theme-default-light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#501810">

    <title inertia>Coffee Globe - {{ __('coffee_trading_company') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('images/icon.svg') }}">

    {{-- hreflang tags with correct alternate URLs --}}
    <link rel="alternate" hreflang="ar" href="{{ localizedUrl('ar') }}">
    <link rel="alternate" hreflang="en" href="{{ localizedUrl('en') }}">
    <link rel="alternate" hreflang="x-default" href="{{ localizedUrl('ar') }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Coffee Globe">
    <meta property="og:locale" content="{{ app()->getLocale() === 'ar' ? 'ar_SA' : 'en_US' }}">
    <meta property="og:locale:alternate" content="ar_SA">
    <meta property="og:locale:alternate" content="en_US">
    <meta property="og:image" content="{{ asset('images/bg_slide.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@coffeeglobe">

    <link rel="preconnect" href="https://coffeeglobe.sa">
    <link rel="dns-prefetch" href="https://coffeeglobe.sa">

    <link rel="manifest" href="/manifest.json">

    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/normalized.css') }}" />
    <link rel="preload" as="style" href="{{ asset('css/cairo.css') }}" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('css/cairo.css') }}"></noscript>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <script src="{{ asset('assets/vendor/js/helpers.js') }}" defer></script>

    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>
