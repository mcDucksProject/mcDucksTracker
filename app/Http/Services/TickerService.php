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
    const MAX_TICKER_AGE = 3;
    private BinanceService $binanceService;
    private PairService $pairService;

    function __construct(BinanceService $binanceService, PairService $pairService)
    {
        $this->binanceService = $binanceService;
        $this->pairService = $pairService;
    }

    public function getPriceByPair(Pair $pair)
    {
        $now = new Carbon();
        $ticker = Ticker::wherePairId($pair->id)->first();
        if ($this->isTickerOutdated($ticker, $now)) {
            $this->updateTickers();
            return Ticker::wherePairId($pair->id)->first();
        }
        return $ticker;
    }

    private function isTickerOutdated($lastTicker, Carbon $now): bool
    {
        return is_null($lastTicker) || $now->diffInMinutes($lastTicker->ticker_date, true) >= self::MAX_TICKER_AGE;
    }

    function updateTickers(): JsonResponse
    {
        $now = new Carbon();
        $lastTicker = Ticker::first();
        if ($this->isTickerOutdated($lastTicker, $now)) {
            $pairs = $this->pairService->getAll();
            $tickersData = $this->binanceService->getTickersData($pairs);
            $tickers = $this->parsePairPrices($tickersData, $now);
            Ticker::truncate();
            Ticker::insert($tickers);
        }
        return new JsonResponse();
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
