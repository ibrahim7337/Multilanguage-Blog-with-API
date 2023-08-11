<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLanguageApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $languages = array_keys(config('app.languages'));
        if ($request->hasHeader('lang') && in_array($request->header('lang') , $languages)) {
            app()->setLocale($request->header('lang'));
        }
        return $next($request);
    }
}