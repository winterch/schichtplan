<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BasicAuthFromEnv
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $users = config('basicauth.users');

        if ($users->contains($request->getUser(), $request->getPassword())) {
            return $next($request);
        }
        
        return response('Ungültige Zugangsdaten', 401, ['WWW-Authenticate' => 'Basic']);
    }
}
