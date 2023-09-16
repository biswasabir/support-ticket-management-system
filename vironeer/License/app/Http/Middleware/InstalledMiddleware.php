<?php

namespace Vironeer\License\App\Http\Middleware;

use Closure;

class InstalledMiddleware
{
    public function handle($request, Closure $next)
    {
        if (systemInfo()->status) {
            return redirect('/');
        }
        return $next($request);
    }
}
