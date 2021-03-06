<?php

namespace App\Http\Services;

use App\Exceptions\SaveException;
use App\Exceptions\UpdateException;
use App\Models\Portfolio;
use App\Models\Position;
use App\Models\Token;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;


class PositionService
{
    /**
     * @throws SaveException
     */
    function create(Token $token, $userId,Portfolio $portfolio, $status): Position
    {
        try {
            $position = new Position();
            $position->token_id = $token->id;
            $position->user_id = $userId;
            $position->portfolio_id = $portfolio->id;
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
        /** @var Position $position */
        return $this->addRelations(Position::whereId($positionId))->firstOrFail();
    }

    function getByPortfolio($portfolioId): Collection
    {
        return $this->addRelations(Position::wherePortfolioId($portfolioId))->get();
    }

    function getByUser($userId): Collection
    {
        return $this->addRelations(Position::whereUserId($userId))->get();
    }

    private function addRelations(Builder $builder): Builder
    {
        return $builder->with([
            "token",
            "token.pairs",
            "token.pairs.base",
            "token.pairs.quote",
            "orders.prices"
        ]);
    }
}
