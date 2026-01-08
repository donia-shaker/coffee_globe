<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style  layout-menu-fixed   " dir="rtl"
    data-theme="theme-default" data-template="vertical-menu-theme-default-light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>Yemen Coffee Trading </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    <meta property="og:title" content='Yemen Coffee Trading' />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}" />
    <!-- CSS Normalized -->
    <link rel="stylesheet" href="{{ asset('css/normalized.css') }}" />
    <!-- Fonts -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/coref43c.css') }}"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default56b8.css') }}"
        class="template-customizer-theme-css" /> --}}
    <link rel="stylesheet" href="{{ asset('css/cairo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Scripts -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    {{-- <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <script>
        window.templateCustomizer = new TemplateCustomizer({
            cssPath: '',
            themesPath: '',
            defaultShowDropdownOnHover: true, // true/false (for horizontal layout only)
            displayCustomizer: true,
            lang: 'en',
            pathResolver: function(path) {
                var resolvedPaths = {
                    // Core stylesheets
                    'core.css': "{{ asset('assets/css/core.css') }}",
                    'core-dark.css': "{{ asset('assets/css/core-dark.css') }}",

                    // Themes
                    'theme-default.css': '{{ asset('assets/css/theme-default.css') }}',
                    'theme-default-dark.css': '{{ asset('assets/css/theme-default-dark.css') }}',
                    'theme-bordered.css': '{{ asset('assets/css/theme-bordered.css') }}',
                    'theme-bordered-dark.css': '{{ asset('assets/css/theme-bordered-dark.css') }}',
                    'theme-semi-dark.css': '{{ asset('assets/css/theme-semi-dark.css') }}',
                    'theme-semi-dark-dark.css': '{{ asset('assets/css/theme-semi-dark-dark.css') }}',
                }
                return resolvedPaths[path] || path;
            },
            'controls': ["rtl", "style", "layoutType", "showDropdownOnHover", "layoutNavbarFixed",
                "layoutFooterFixed", "themes"
            ],
        });
    </script> --}}
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>
