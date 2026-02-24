<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->is_banned) {
            auth()->logout();
            return redirect()->route('login')->withErrors([
                'account' => 'Votre compte a été banni par un administrateur.'
            ]);
        }
        return $next($request);
    }
}
