<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->segment(1);

        if (in_array($locale, ['ro', 'en'])) {
            app()->setLocale($locale);
        } else {
            return redirect('/' . config('app.locale') . $request->getRequestUri());
        }

        return $next($request);
    }
}
