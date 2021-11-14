<?php

namespace App\Http\Services;

use App\Http\Services\Exchange\BinanceService;
use App\Models\Pair;
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
    public function getPriceByPair(Pair $pair){

    }
    private function parsePairPrices(Collection $tickersData, Carbon $tickerDate): array
    {
        return $tickersData->map(function ($tickerData) use ($tickerDate) {
            return [
                'pair_id' => $tickerData['pair']->id,
                'ticker_date' => $tickerDate,
                'price' => $tickerData['price']
            ];
        })->all();
    }

}
