<?php

namespace App\Http\Services;

use App\Http\Services\Exchange\BinanceService;
use App\Models\Ticker;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class TickerService
{
    private BinanceService $binanceService;
    private PairService $pairService;

    function __construct(BinanceService $binanceService, PairService $pairService)
    {
        $this->binanceService = $binanceService;
        $this->pairService = $pairService;
    }

    function updateTickers(): JsonResponse
    {
        $now = new Carbon();
        $lastTicker = Ticker::first();
        if (is_null($lastTicker) || $now->diffInMinutes($lastTicker->date, true) >= 1) {
            $pairs = $this->pairService->getAll();
            $tickersData = $this->binanceService->getTickersData($pairs);
            $tickers = $this->parsePairPrices($tickersData, $now);
            Ticker::truncate();
            Ticker::insert($tickers);
        }
        return new JsonResponse();
    }

    private function parsePairPrices(Collection $tickersData, Carbon $now): array
    {
        return $tickersData->map(function ($tickerData) use ($now) {
            return [
                'pair_id' => $tickerData['pair']->id,
                'date' => $now,
                'price' => $tickerData['price']
            ];
        })->all();
    }
}
