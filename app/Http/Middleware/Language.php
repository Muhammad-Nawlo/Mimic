<?php

namespace App\Http\Middleware;

use Closure;

class Language
{
    protected const ACCEPTED_LANGUAGES = ['ar', 'en'];

    public function handler($request, Closure $next)
    {
        $language = $request->header('Accept-Language');
        $locale = in_array($language, self::ACCEPTED_LANGUAGES) ? $language : 'en';
        session('locale', $locale);
        app()->setLocale($locale);
        return $next($request);
    }
}
