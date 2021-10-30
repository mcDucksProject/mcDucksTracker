<?php

namespace App\Http\Services;

use App\Exceptions\SaveException;
use App\Exceptions\UpdateException;
use App\Models\Portfolio;
use App\Models\Position;
use App\Models\Token;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;


class PositionService
{
    /**
     * @throws SaveException
     */
    function create($tokenId, $userId, $portfolioId, $status): Position
    {
        try {
            $position = new Position();
            $position->token_id = Token::findOrFail($tokenId)->id;
            $position->user_id = $userId;
            $position->portfolio_id = Portfolio::findOrFail($portfolioId)->id;
            $position->status = $status;
            $position->saveOrFail();
        } catch (Throwable | ModelNotFoundException $e) {
            throw new SaveException($e->getMessage());
        }
        return $position;
    }

    /**
     * @throws UpdateException
     */
    function update($positionId, $status): Position
    {
        try {
            $position = Position::findOrFail($positionId);
            $position->status = $status;
            $position->saveOrFail();
        } catch (Throwable $e) {
            throw new UpdateException();
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
