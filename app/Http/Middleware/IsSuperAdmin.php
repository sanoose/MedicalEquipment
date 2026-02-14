<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || !in_array($user->user_level, [1 ])) {
            abort(403, 'غير مصرح لك بالدخول إلى هذه الصفحة.');
        }
    
        return $next($request);
    }
}