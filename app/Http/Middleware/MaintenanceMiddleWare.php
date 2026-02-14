<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Maintenance;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $maintenance = Maintenance::first();
        
        // إذا كان الخادم في وضع الصيانة والمستخدم ليس سوبر أدمن
        if ($maintenance && $maintenance->is_maintenance == 2 && !$request->user()?->isSuperAdmin()) {
            return redirect()->route('Maintenance.MaintenancePage');
        }

        return $next($request);
    }
}