<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check()) {

            $user = Auth::user();

            $firstSegment = $request->segment(1);

            if ($user->isAdmin() && $firstSegment !== 'admin') {
                return redirect()->route('admin');
            } elseif ($user->isAgent() && $firstSegment !== 'agent') {
                return redirect()->route('agent');
            } elseif ($user->isUser()) {
                return redirect()->route('user');
            }

        }

        return $response;
    }
}
