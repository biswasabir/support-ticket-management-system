<?php

namespace App\Http\Middleware\Actions;

use Closure;
use Illuminate\Http\Request;

class RegistrationAction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        abort_if(!settings('actions')->registration_status, 404);
        return $next($request);
    }
}
