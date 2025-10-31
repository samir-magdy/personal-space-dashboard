<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    protected $key;
    protected $url;

    public function __construct()
    {
        $this->key = config('services.weather.key');
        $this->url = config('services.weather.URL');
    }

    public function get_weather_data(string $city)
    {
        // Append country code EG to ensure only Egyptian cities are returned
        $cityWithCountry = $city . ',EG';

        $response = Http::get(
            "{$this->url}/weather",
            [
                'q' => $cityWithCountry,
                'appid' => $this->key,
                'units' => 'metric'
            ]
        );

        if ($response->successful()) {
            return [
                'city' => $response->json('name'),
                'country' => $response->json('sys.country'),
                'temp' => $response->json('main.temp'),
                'description' => $response->json('weather.0.description'),
                'icon' => $response->json('weather.0.icon'),
            ];
        }

        return null;
    }
};
