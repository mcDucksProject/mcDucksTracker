<?php

namespace App\Http\Services;

use App\Exceptions\DeleteException;
use App\Exceptions\SaveException;
use App\Models\HistoricalPrice;
use App\Models\Pair;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HistoricalPriceService
{
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
}
