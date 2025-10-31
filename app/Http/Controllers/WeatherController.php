<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function fetchWeather(Request $request)
    {
        $validated = $request->validate([
            'city' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/'
        ]);

        try {
            $weather = $this->weatherService->get_weather_data($validated['city']);

            if ($weather) {
                return response()->json([
                    'success' => true,
                    'weather' => $weather
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Sorry, data for this city is not available.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch weather data: ' . $e->getMessage()
            ], 500);
        }
    }
}
