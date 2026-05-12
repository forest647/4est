<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockIp
{
    public function handle(Request $request, Closure $next): Response
    {
        if (BlockedIp::where('ip', $request->ip())->exists()) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}
