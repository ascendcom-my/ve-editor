<?php

namespace Bigmom\VeEditor\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EnsureUserIsAuthorized
{
    /**
     * Ensures the user is authorized to visit Vapor UI Dashboard.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (config('ve.restrict-usage')) {
            $allowed = app()->environment('local')
                || Gate::forUser(Auth::guard('ve-editor')->user())->allows('accessVeEditor');

            abort_unless($allowed, 403);
        }

        return $next($request);
    }
}
