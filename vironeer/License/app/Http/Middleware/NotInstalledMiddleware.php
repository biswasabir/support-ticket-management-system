<?php

namespace Vironeer\License\App\Http\Middleware;

use Closure;

class NotInstalledMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!systemInfo()->status) {
            return redirect()->route('install.index');
        }
        return $next($request);
    }
}
