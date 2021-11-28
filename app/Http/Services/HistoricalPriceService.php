<?php

namespace App\Http\Services;

use App\Exceptions\DeleteException;
use App\Exceptions\NotFoundException;
use App\Exceptions\SaveException;
use App\Http\Services\Exchange\BinanceService;
use App\Models\HistoricalPrice;
use App\Models\Pair;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\ItemNotFoundException;
use Throwable;

class HistoricalPriceService
{
    const TIMEFRAME = '1d';
    const MAX_CANDLES = 365;
    const MAX_DIF_IN_HOURS = 0;
    private BinanceService $binanceService;

    function __construct(BinanceService $binanceService)
    {
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

    /**
     * @throws NotFoundException
     */
    function findByPair($pairId): Collection
    {
        $historicalPrices = HistoricalPrice::wherePairId($pairId)->orderByDesc('price_date')->get();
        if ($historicalPrices->isEmpty()) {
            throw new NotFoundException();
        }
        return $historicalPrices;
    }

    /**
     * @throws SaveException
     */
    function updateHistoricalPrice(Pair $pair): bool
    {
        return $this->updateHistoricalPrices(collect([$pair]));
    }

    /**
     * @throws SaveException
     */
    function updateHistoricalPrices(Collection $pairs): bool
    {
        $earliestDate = new Carbon();
        $pairsToUpdate = $pairs->mapToGroups(function (Pair $pair) use (&$earliestDate) {
            return $this->mapPairToUpdateGroup($pair, $earliestDate);
        });

        if ($pairsToUpdate->has('update')) {
            $updatedHistoricalData = $this->getHistoricalData(
                $pairsToUpdate->get('update'),
                $earliestDate
            );
            if (!HistoricalPrice::updateOrInsert(['pair_id', 'price_date'], $updatedHistoricalData)) {
                throw new SaveException("There was a problem updating historical data");
            }
        }
        if ($pairsToUpdate->has('new')) {
            $since = new Carbon();
            $newHistoricalData = $this->getHistoricalData(
                $pairsToUpdate->get('new'),
                $since->subDays(self::MAX_CANDLES)
            );
            if (!HistoricalPrice::insert($newHistoricalData->toArray())) {
                throw new SaveException("There was a problem creating new historical data");
            }
        }
        return true;
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

    private function mapPairToUpdateGroup(Pair $pair, Carbon &$earliestDate): array
    {
        try {
            $lastHistoricalPrice = $this->findByPair($pair)->firstOrFail();
        } catch (NotFoundException | ItemNotFoundException $exception) {
            return ['new' => $pair];
        }
        if ($lastHistoricalPrice->price_date->diffInHours(new Carbon()) <= self::MAX_DIF_IN_HOURS) {
            return ['ignore' => $pair];
        }
        $earliestDate = $earliestDate->diffInHours($lastHistoricalPrice) < 0
            ? $lastHistoricalPrice
            : $earliestDate;
        return ['update' => $pair];
    }
}
