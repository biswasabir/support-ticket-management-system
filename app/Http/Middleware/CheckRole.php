<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $roles)
    {
        if (Auth::check()) {

            $user = Auth::user();

            $firstSegment = $request->segment(1);

            if ($user->isAdmin() && $firstSegment !== 'admin') {
                return redirect()->route('admin');
            } elseif ($user->isAgent() && $firstSegment !== 'agent') {
                return redirect()->route('agent');
            } elseif ($user->isUser() && $firstSegment !== 'user') {
                return redirect()->route('user');
            }

        }

        return $next($request);
    }
}
