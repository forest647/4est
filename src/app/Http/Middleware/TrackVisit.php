<?php

namespace App\Http\Middleware;

use App\Models\Statistic;
use App\Services\GeoLocationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisit
{
    public function __construct(private GeoLocationService $geoService) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            $ip = $request->ip();
            $geo = $this->geoService->lookup($ip);

            Statistic::create([
                'ip' => $ip,
                'city' => $geo['city'],
                'state' => $geo['state'],
                'country' => $geo['country'],
                'continent' => $geo['continent'],
                'page' => $request->path(),
            ]);
        } catch (\Exception $e) {
            // Don't let tracking failures break the site
        }

        return $response;
    }
}
