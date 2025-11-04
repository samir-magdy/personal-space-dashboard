<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $city = request('city', 'Cairo');
        $baseCurrency = request('base_currency', 'USD');
        $targetCurrency = request('target_currency', 'EGP');
        $newsLang = request('news_lang', 'en');

        // Check cache for all data
        $weather = cache()->get("weather_{$city}");
        $currency = cache()->get("currency_{$baseCurrency}_{$targetCurrency}");
        $availableCurrencies = cache()->get('available_currencies');
        $news = cache()->get("news_{$newsLang}");

        // Identify what needs to be fetched
        $needsWeather = is_null($weather);
        $needsCurrency = is_null($currency);
        $needsAvailableCurrencies = is_null($availableCurrencies);
        $needsNews = is_null($news);

        // Make parallel API calls for cache misses
        if ($needsWeather || $needsCurrency || $needsAvailableCurrencies || $needsNews) {
            $responses = Http::pool(fn ($pool) => [
                $needsWeather ? $pool->get(
                    config('services.weather.URL') . '/weather',
                    [
                        'q' => $city . ',EG',
                        'appid' => config('services.weather.key'),
                        'units' => 'metric'
                    ]
                ) : null,
                $needsCurrency ? $pool->get(
                    config('services.currency.URL') . config('services.currency.key') . '/latest/' . $baseCurrency
                ) : null,
                $needsAvailableCurrencies ? $pool->get(
                    config('services.currency.URL') . config('services.currency.key') . '/codes'
                ) : null,
                $needsNews ? $pool->get(
                    config('services.news.URL') . 'top-headlines',
                    [
                        'category' => 'general',
                        'lang' => $newsLang,
                        'max' => 2,
                        'apikey' => config('services.news.key'),
                    ]
                ) : null,
            ]);

            // Map responses to named keys
            $responseIndex = 0;
            $weatherResponse = $needsWeather ? $responses[$responseIndex++] : null;
            $currencyResponse = $needsCurrency ? $responses[$responseIndex++] : null;
            $availableCurrenciesResponse = $needsAvailableCurrencies ? $responses[$responseIndex++] : null;
            $newsResponse = $needsNews ? $responses[$responseIndex++] : null;

            // Process weather response
            if ($needsWeather && $weatherResponse && $weatherResponse->successful()) {
                $weather = [
                    'city' => $weatherResponse->json('name'),
                    'country' => $weatherResponse->json('sys.country'),
                    'temp' => $weatherResponse->json('main.temp'),
                    'description' => $weatherResponse->json('weather.0.description'),
                    'icon' => $weatherResponse->json('weather.0.icon'),
                ];
                cache()->put("weather_{$city}", $weather, now()->addMinutes(30));
            }

            // Process currency response
            if ($needsCurrency && $currencyResponse && $currencyResponse->successful() && $currencyResponse->json('result') === 'success') {
                $rates = $currencyResponse->json('conversion_rates');
                $currency = [
                    'base_currency' => $baseCurrency,
                    'target_currency' => $targetCurrency,
                    'rate' => $rates[$targetCurrency] ?? null,
                    'last_update' => $currencyResponse->json('time_last_update_utc'),
                ];
                cache()->put("currency_{$baseCurrency}_{$targetCurrency}", $currency, now()->addMinutes(30));
            }

            // Process available currencies response
            if ($needsAvailableCurrencies && $availableCurrenciesResponse && $availableCurrenciesResponse->successful() && $availableCurrenciesResponse->json('result') === 'success') {
                $codes = $availableCurrenciesResponse->json('supported_codes');
                $currencies = [];
                foreach ($codes as $code) {
                    $currencies[$code[0]] = $code[1];
                }
                $availableCurrencies = $currencies;
                cache()->put('available_currencies', $availableCurrencies, now()->addHours(24));
            }

            // Process news response
            if ($needsNews && $newsResponse && $newsResponse->successful()) {
                $articles = $newsResponse->json('articles', []);
                $news = array_map(function ($article) {
                    return [
                        'title' => $article['title'] ?? null,
                        'description' => $article['description'] ?? null,
                        'url' => $article['url'] ?? null,
                        'image' => $article['image'] ?? null,
                        'published_at' => $article['publishedAt'] ?? null,
                        'source' => $article['source']['name'] ?? 'Unknown',
                    ];
                }, $articles);
                cache()->put("news_{$newsLang}", $news, now()->addMinutes(30));
            }
        }

        return view('dashboard', [
            'weather' => $weather,
            'currency' => $currency,
            'availableCurrencies' => $availableCurrencies ?? [],
            'news' => $news ?? []
        ]);
    }
}
