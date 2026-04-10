<?php

use App\Models\Lang;

function getLangs()
{
    return Lang::get();
}


function getFieldVal($val) {

    $locale = app()->getLocale() ?? 'ar';
   return is_array($val) ? ($val[$locale] ?? $val['en'] ?? $val['ur'] ?? $val) : $val;

    
}

/**
 * Generate a localized URL for hreflang tags.
 * Returns the URL with the appropriate locale prefix.
 */
function localizedUrl(string $locale, ?string $path = null): string
{
    $baseUrl = config('app.url', 'https://coffeeglobe.sa');
    $path = $path ?? request()->path();

    // Remove any existing locale prefix from the path
    if (str_starts_with($path, 'en/') || $path === 'en') {
        $path = substr($path, 3);
    }

    $path = ltrim($path, '/');

    if ($locale === 'en') {
        return rtrim($baseUrl . '/en/' . $path, '/');
    }

    return rtrim($baseUrl . '/' . $path, '/') ?: $baseUrl;
}

/**
 * Get the current locale from the URL.
 */
function currentLocale(): string
{
    $path = request()->path();
    if (str_starts_with($path, 'en/') || $path === 'en') {
        return 'en';
    }
    return 'ar';
}