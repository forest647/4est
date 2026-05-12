<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeoLocationService
{
    public function lookup(string $ip): array
    {
        try {
            $response = Http::timeout(2)->get("http://www.geoplugin.net/json.gp?ip={$ip}");
            $data = $response->json();

            return [
                'city' => $data['geoplugin_city'] ?? '',
                'state' => $data['geoplugin_region'] ?? '',
                'country' => $data['geoplugin_countryName'] ?? '',
                'continent' => $data['geoplugin_continentName'] ?? '',
            ];
        } catch (\Exception $e) {
            return ['city' => '', 'state' => '', 'country' => '', 'continent' => ''];
        }
    }
}
