<?php

namespace App\Http\Services;

use App\Exceptions\DeleteException;
use App\Exceptions\SaveException;
use App\Http\Services\exchanges\BinanceService;
use App\Models\HistoricalPrice;
use App\Models\Pair;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class HistoricalPriceService
{
    const TIMEFRAME = '1d';
    const MAX_CANDLES = 365;
    private PairService $pairService;
    private BinanceService $binanceService;

    function __construct(PairService $pairService, BinanceService $binanceService)
    {
        $this->pairService = $pairService;
        $this->binanceService = $binanceService;
    }

    /**
     * @throws SaveException
     */
    function create(int $pairId, $date, float $price): HistoricalPrice
    {
        try {
            $historicalPrice = new HistoricalPrice();
            $historicalPrice->pair_id = Pair::findOrFail($pairId)->id;
            $historicalPrice->date = $date;
            $historicalPrice->price = $price;
            $historicalPrice->saveOrFail();
        } catch (\Throwable $e) {
            throw new SaveException();
        }
        return $historicalPrice;
    }

    /**
     * @throws DeleteException
     */
    function delete(int $historicalPriceId)
    {
        try {
            HistoricalPrice::findOrFail($historicalPriceId)->deleteOrFail();
        } catch (ModelNotFoundException | \Throwable $e) {
            throw new DeleteException();
        }
    }

    function findByPairAndDate($pairId, $date): Collection
    {
        return HistoricalPrice::wherePairId($pairId)->whereDate($date)->get();
    }

    function findByPairBetweenDates($pairId, $startDate, $endDate): Collection
    {
        return HistoricalPrice::wherePairId($pairId)
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)->get();
    }

    function findByPair($pairId): Collection
    {
        return HistoricalPrice::wherePairId($pairId)->get();
    }

    function updateHistoricalPrices(): bool
    {
        $pairs = $this->pairService->getAll();
        /** @var HistoricalPrice $lastPrice */
        $lastPrice = HistoricalPrice::orderBy('date', 'desc')->first();
        if (!is_null($lastPrice) && $lastPrice->date->diffInDays(new Carbon()) <= 0) {
            return false;
        }
        if (is_null($lastPrice)) {
            $originalDate = new Carbon();
            $since = $originalDate->subDays(self::MAX_CANDLES);
        } else {
            $since = $lastPrice->date;
        }
        $historicalData = $this->getHistoricalData($pairs, $since);
        return HistoricalPrice::insert(
            $historicalData->toArray()
        );
    }

    private function getHistoricalData(Collection $pairs, $since): Collection
    {

        return $this->binanceService->getHistoricalData($pairs,
            self::TIMEFRAME,
            $since,
            self::MAX_CANDLES)->flatMap(function ($historicalData) {

            return $historicalData['data']->map(function ($data) use ($historicalData) {
                return [
                    'pair_id' => $historicalData['pair']->id,
                    'price' => $data['price'],
                    'date' => $data['date']
                ];
            });
        });
    }
}
