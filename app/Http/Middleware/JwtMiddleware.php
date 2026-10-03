<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            if ($token = $request->cookie('token')) {
                $request->headers->set('Authorization', 'Bearer ' . $token);
            }

            $user = JWTAuth::parseToken()->authenticate();

            if ($user->role === 'admin' && !$request->is('admin*')) {
                return redirect()->route('admins.dashboard');
            }

        } catch (Exception $e) {
            if ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException){
                return redirect()->route('auth.login')->with('error', 'Token tidak valid');
            } else if ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException){
                return redirect()->route('auth.login')->with('error', 'Sesi berakhir, silakan login lagi');
            } else {
                return redirect()->route('auth.login')->with('error', 'Silakan login terlebih dahulu');
            }
        }
        return $next($request);
    }
}
