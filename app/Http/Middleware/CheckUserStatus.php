<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check user status after authentication
        if (auth()->check()) {
            $user = auth()->user();
            
            // Check if user is pending
            if ($user->status === 'pending') {
                auth()->logout();
                return redirect('/login')->with('error', 'Akun Anda belum di-accept oleh Owner. Mohon tunggu.');
            }
            
            // Check if user has no role assigned
            if (!$user->role_id) {
                auth()->logout();
                return redirect('/login')->with('error', 'Akun Anda belum di-assign role oleh Owner. Mohon tunggu.');
            }
        }
        
        return $next($request);
    }
}
