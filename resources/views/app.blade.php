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

    {{-- Phase 4: Preload LCP image with fetchpriority --}}
    <link rel="preload" as="image" href="{{ asset('images/logo.webp') }}" type="image/webp" fetchpriority="high">

    {{-- Phase 3: Preload critical Cairo fonts (SemiBold and Bold are most used) --}}
    <link rel="preload" href="{{ asset('fonts/Cairo/Cairo-SemiBold.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/Cairo/Cairo-Bold.woff2') }}" as="font" type="font/woff2" crossorigin>

    {{-- Phase 2: Inline critical CSS to eliminate render-blocking resources --}}
    <style>
        /* Critical CSS - inlined from normalized.css + style.css */
        html{line-height:1.15;-webkit-text-size-adjust:100%}
        body{margin:0;font-family:"Cairo",sans-serif!important}
        main{display:block}
        h1{font-size:2em;margin:.67em 0}
        hr{box-sizing:content-box;height:0;overflow:visible}
        pre{font-family:monospace,monospace;font-size:1em}
        a{background-color:transparent}
        abbr[title]{border-bottom:none;text-decoration:underline;text-decoration:underline dotted}
        b,strong{font-weight:bolder}
        code,kbd,samp{font-family:monospace,monospace;font-size:1em}
        small{font-size:80%}
        sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}
        sub{bottom:-.25em}
        sup{top:-.5em}
        img{border-style:none}
        button,input,optgroup,select,textarea{font-family:inherit;font-size:100%;line-height:1.15;margin:0}
        button,input{overflow:visible}
        button,select{text-transform:none}
        [type=button],[type=reset],[type=submit],button{-webkit-appearance:button}
        [type=button]::-moz-focus-inner,[type=reset]::-moz-focus-inner,[type=submit]::-moz-focus-inner,button::-moz-focus-inner{border-style:none;padding:0}
        [type=button]:-moz-focusring,[type=reset]:-moz-focusring,[type=submit]:-moz-focusring,button:-moz-focusring{outline:1px dotted ButtonText}
        fieldset{padding:.35em .75em .625em}
        legend{box-sizing:border-box;color:inherit;display:table;max-width:100%;padding:0;white-space:normal}
        progress{vertical-align:baseline}
        textarea{overflow:auto}
        [type=checkbox],[type=radio]{box-sizing:border-box;padding:0}
        [type=number]::-webkit-inner-spin-button,[type=number]::-webkit-outer-spin-button{height:auto}
        [type=search]{-webkit-appearance:textfield;outline-offset:-2px}
        [type=search]::-webkit-search-decoration{-webkit-appearance:none}
        ::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}
        details{display:block}
        summary{display:list-item}
        template{display:none}
        [hidden]{display:none}
    </style>

    {{-- Phase 2: Defer non-critical Font Awesome CSS --}}
    <link rel="preload" href="{{ asset('css/all.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('css/all.min.css') }}"></noscript>

    {{-- Phase 3: Load Cairo fonts with swap (already has font-display:swap) --}}
    <link rel="preload" as="style" href="{{ asset('css/cairo.css') }}" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('css/cairo.css') }}"></noscript>

    {{-- Phase 3: Preload Font Awesome woff2 fonts to reduce critical chain --}}
    <link rel="preload" href="{{ asset('webfonts/fa-solid-900.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('webfonts/fa-brands-400.woff2') }}" as="font" type="font/woff2" crossorigin>

    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>
