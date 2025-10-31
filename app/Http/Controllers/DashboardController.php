<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use App\Services\CurrencyService;
use App\Services\NewsService;

class DashboardController extends Controller
{
    protected $weatherService;
    protected $currencyService;
    protected $newsService;

    public function __construct(WeatherService $weatherService, CurrencyService $currencyService, NewsService $newsService)
    {
        $this->weatherService = $weatherService;
        $this->currencyService = $currencyService;
        $this->newsService = $newsService;
    }

    public function index()
    {
        $city = request('city', 'Cairo');
        $weather = cache()->remember("weather_{$city}", now()->addMinutes(30), function () use ($city) {
            return $this->weatherService->get_weather_data($city);
        });

        $baseCurrency = request('base_currency', 'USD');
        $targetCurrency = request('target_currency', 'EGP');
        $currency = cache()->remember("currency_{$baseCurrency}_{$targetCurrency}", now()->addMinutes(30), function () use ($baseCurrency, $targetCurrency) {
            return $this->currencyService->get_exchange_rates($baseCurrency, $targetCurrency);
        });
        $availableCurrencies = cache()->remember('available_currencies', now()->addHours(24), function () {
            return $this->currencyService->get_available_currencies();
        });

        $newsLang = request('news_lang', 'en');
        $news = $this->newsService->get_top_headlines('general', $newsLang, 2);


        return view('dashboard', [
            'weather' => $weather,
            'currency' => $currency,
            'availableCurrencies' => $availableCurrencies,
            'news' => $news
        ]);
    }
}
