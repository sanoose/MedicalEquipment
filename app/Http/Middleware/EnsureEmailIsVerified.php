<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user =  Auth::user() ;

        if (!$user || $user->email_verified != 1) {
            return redirect()->route('Email.ResendVerificationForm')->withErrors([
                'email' => 'يرجى تفعيل بريدك الإلكتروني أولاً.'
            ]);
        }

        return $next($request);
    }
}
