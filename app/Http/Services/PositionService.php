<?php

namespace App\Http\Services;

use App\Exceptions\SaveException;
use App\Models\Holding;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class PositionService
{
    /**
     * @throws SaveException
     */
    function create($portfolioId, $token, $pair, $expectedSell): Holding
    {
        $position = new Holding([
            'portfolio_id' => $portfolioId,
            'token' => $token,
            'pair' => $pair,
            'expected_sell' => $expectedSell,
            'user_id' => \Auth::id()
        ]);

        try {
            $position->saveOrFail();
        } catch (\Throwable $e) {
            throw new SaveException();
        }
        return $position;
    }

    /**
     * @throws SaveException
     */
    function update($positionId, $expectedSell): Holding
    {
        try {
            $position = Holding::findOrFail($positionId);
            $position->expected_sell = $expectedSell;
            $position->saveOrFail();
        } catch (\Throwable $e) {
            throw new SaveException();
        }
        return $position;
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
}
