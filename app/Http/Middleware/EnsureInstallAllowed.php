<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInstallAllowed
{
    public function handle(Request $request, Closure $next): Response
    {
        if (env('APP_INSTALLED') === 'true') {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
