<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     * Sets the application locale based on the URL prefix.
     * Arabic is the default (no prefix), English uses /en/ prefix.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();
        $locale = 'ar'; // default

        // Check if the URL starts with 'en/'
        if (str_starts_with($path, 'en') && (str_starts_with($path, 'en/') || $path === 'en')) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
