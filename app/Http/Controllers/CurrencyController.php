<?php

namespace App\Http\Controllers;

use App\Services\CurrencyService;

class CurrencyController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function fetchRates()
    {
        $baseCurrency = request('base_currency', 'USD');
        $targetCurrency = request('target_currency', 'EGP');

        try {
            $currency = $this->currencyService->get_exchange_rates($baseCurrency, $targetCurrency);

            if ($currency) {
                return response()->json([
                    'success' => true,
                    'currency' => $currency
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Currency data not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch currency data: ' . $e->getMessage()
            ], 500);
        }
    }
}
