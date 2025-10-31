<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CurrencyService
{
    protected $key;
    protected $url;

    public function __construct()
    {
        $this->key = config('services.currency.key');
        $this->url = config('services.currency.URL');
    }

    public function get_exchange_rates(string $baseCurrency = 'USD', string $targetCurrency = 'EGP')
    {
        $response = Http::get(
            "{$this->url}{$this->key}/latest/{$baseCurrency}"
        );

        if ($response->successful() && $response->json('result') === 'success') {
            $rates = $response->json('conversion_rates');

            return [
                'base_currency' => $baseCurrency,
                'target_currency' => $targetCurrency,
                'rate' => $rates[$targetCurrency] ?? null,
                'last_update' => $response->json('time_last_update_utc'),
            ];
        }

        return null;
    }

    public function get_available_currencies()
    {
        $response = Http::get(
            "{$this->url}{$this->key}/codes"
        );

        if ($response->successful() && $response->json('result') === 'success') {
            $codes = $response->json('supported_codes');

            // Convert array of [code, name] to associative array
            $currencies = [];
            foreach ($codes as $code) {
                $currencies[$code[0]] = $code[1];
            }

            return $currencies;
        }

        return [];
    }
}
