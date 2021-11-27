<?php

namespace App\Http\Services;

use App\Exceptions\DeleteException;
use App\Exceptions\SaveException;
use App\Http\Services\Exchange\BinanceService;
use App\Models\HistoricalPrice;
use App\Models\Pair;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Throwable;

class HistoricalPriceService
{
    const TIMEFRAME = '1d';
    const MAX_CANDLES = 365;
    const MAX_DIF_IN_HOURS = 0;
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
            $historicalPrice->price_date = $date;
            $historicalPrice->price = $price;
            $historicalPrice->saveOrFail();
        } catch (Throwable $e) {
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
        } catch (ModelNotFoundException | Throwable $e) {
            throw new DeleteException();
        }
    }

    function findByPairAndDate(int $pairId, Carbon $date): Collection
    {
        return HistoricalPrice::wherePairId($pairId)->whereDate('price_date', $date->startOfDay())->get();
    }

    function findByPairBetweenDates($pairId, $startDate, $endDate): Collection
    {
        return HistoricalPrice::wherePairId($pairId)
            ->where('price_date', '>=', $startDate)
            ->where('price_date', '<=', $endDate)
            ->get();
    }

    function findByPair($pairId): Collection
    {
        return HistoricalPrice::wherePairId($pairId)->get();
    }

    function updateHistoricalPrices(): bool
    {
        $pairs = $this->pairService->getAll();
        /** @var HistoricalPrice $lastPrice */
        $lastPrice = HistoricalPrice::orderBy('price_date', 'desc')->first();

        if (is_null($lastPrice)) {
            $originalDate = new Carbon();
            $since = $originalDate->subDays(self::MAX_CANDLES);
        } else {
            if ($lastPrice->price_date->diffInHours(new Carbon()) <= self::MAX_DIF_IN_HOURS) {
                return false;
            }
            $since = $lastPrice->price_date;
        }
        $historicalData = $this->getHistoricalData($pairs, $since);
        return HistoricalPrice::insert(
            $historicalData->toArray()
        );
    }

    private function getHistoricalData(Collection $pairs, $since): Collection
    {
        $now = new Carbon();
        return $this->binanceService->getHistoricalData($pairs,
            self::TIMEFRAME,
            $since)->flatMap(function ($historicalData) use ($now) {

            return $historicalData['data']->map(function ($data) use ($historicalData, $now) {
                return [
                    'pair_id' => $historicalData['pair']->id,
                    'price' => $data['price'],
                    'price_date' => $data['date'],
                    'updated_at' => $now,
                    'created_at' => $now
                ];
            });
        });
    }
}
