<?php

namespace Bigmom\VeEditor\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('ve-editor')->user()) {
            return redirect()->route('ve-editor.getLogin');
        }

        return $next($request);
    }
}