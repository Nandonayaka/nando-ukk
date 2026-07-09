<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfilePicture
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->pfp && !$request->routeIs('pfp.*') && !$request->routeIs('logout')) {
            return redirect()->route('pfp.choose');
        }

        return $next($request);
    }
}
