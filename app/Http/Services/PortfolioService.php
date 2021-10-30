<?php

namespace App\Http\Services;

use App\Exceptions\DeleteException;
use App\Exceptions\SaveException;
use App\Models\Portfolio;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PortfolioService
{
    /**
     * @throws SaveException
     */
    function create($name, $userId, $exchangeId): Portfolio
    {
        $portfolio = new Portfolio();
        $portfolio->user_id = $userId;
        $portfolio->exchange_id = $exchangeId;
        $portfolio->name = $name;
        try {
            $portfolio->saveOrFail();

        } catch (\Throwable $e) {
            throw new SaveException($e->getMessage());
        }

        return $portfolio;
    }

    /**
     * @throws SaveException
     */
    function update($portfolioId, $name): Portfolio
    {
        $portfolio = Portfolio::findOrFail($portfolioId);
        $portfolio->name = $name;
        if ($portfolio->save()) {
            return $portfolio;
        }
        throw new SaveException();
    }

    /**
     * @throws ModelNotFoundException
     */
    function getById($portfolioId): Portfolio
    {
        return Portfolio::findOrFail($portfolioId);
    }

    function getByUserId($userId): Collection
    {
        return Portfolio::whereUserId($userId)->get();
    }

    function getByExchange($exchangeId): Collection
    {
        return Portfolio::whereExchangeId($exchangeId)->get();
    }

    /**
     * @throws DeleteException
     */
    function delete($portfolioId): ?bool
    {
        try {
            $portfolio = $this->getById($portfolioId);
        } catch (ModelNotFoundException $e) {
            throw new DeleteException();
        }
        return $portfolio->delete();
    }
}
