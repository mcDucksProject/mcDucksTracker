<?php

namespace App\Http\Services;

use App\Exceptions\SaveException;
use App\Models\Holding;
use App\Models\Portfolio;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;


class HoldingService
{
    /**
     * @throws SaveException
     */
    function create($portfolioId, $symbol, $expectedSell): Holding
    {
        $holding = new Holding();
        try {
            $holding->portfolio_id = Portfolio::findOrFail($portfolioId)->id;
            $holding->symbol = strtoupper($symbol);
            $holding->expected_sell = $expectedSell;
            $holding->user_id = Auth::id();
            $holding->saveOrFail();
            return $holding;
        } catch (Throwable | ModelNotFoundException $e) {
            throw new SaveException($e->getMessage());
        }
    }

    /**
     * @throws SaveException
     */
    function update($holdingId, $expectedSell): Holding
    {
        try {
            $holding = Holding::findOrFail($holdingId);
            $holding->expected_sell = $expectedSell;
            $holding->saveOrFail();
        } catch (Throwable $e) {
            throw new SaveException();
        }
        return $holding;
    }

    /**
     * @throws ModelNotFoundException
     */
    function getById($positionId): Holding
    {
        return Holding::findOrFail($positionId);
    }

    function getByPortfolio($portfolioId): Collection
    {
        return Holding::wherePortfolioId($portfolioId)->get();
    }

    function getByUser($userId): Collection
    {
        return Holding::whereUserId($userId)->get();
    }
}
