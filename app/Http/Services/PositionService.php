<?php

namespace App\Http\Services;

use App\Exceptions\SaveException;
use App\Models\Position;
use App\Models\Portfolio;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;


class PositionService
{
    /**
     * @throws SaveException
     */
    function create($portfolioId, $symbol, $expectedSell): Position
    {
        $position = new Position();
        try {
            $position->portfolio_id = Portfolio::findOrFail($portfolioId)->id;
            $position->symbol = strtoupper($symbol);
            $position->expected_sell = $expectedSell;
            $position->user_id = Auth::id();
            $position->saveOrFail();
            return $position;
        } catch (Throwable | ModelNotFoundException $e) {
            throw new SaveException($e->getMessage());
        }
    }

    /**
     * @throws SaveException
     */
    function update($holdingId, $expectedSell): Position
    {
        try {
            $position = Position::findOrFail($holdingId);
            $position->expected_sell = $expectedSell;
            $position->saveOrFail();
        } catch (Throwable $e) {
            throw new SaveException();
        }
        return $position;
    }

    /**
     * @throws ModelNotFoundException
     */
    function getById($positionId): Position
    {
        return Position::findOrFail($positionId);
    }

    function getByPortfolio($portfolioId): Collection
    {
        return Position::wherePortfolioId($portfolioId)->get();
    }

    function getByUser($userId): Collection
    {
        return Position::whereUserId($userId)->get();
    }
}
