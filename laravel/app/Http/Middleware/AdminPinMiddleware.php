<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPinMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->get('is_admin') === true) {
            return $next($request);
        }

        // Sla intended URL op en stuur naar PIN-login
        $request->session()->put('intended_url', $request->fullUrl());

        return redirect()->route('admin.login')
            ->with('info', 'Voer de beheercode in om verder te gaan.');
    }
}
