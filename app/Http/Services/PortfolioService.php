<?php

namespace App\Http\Services;

use App\Exceptions\SaveException;
use App\Models\Portfolio;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PortfolioService
{
    /**
     * @throws SaveException
     */
    function create($name, $userId, $exchange): Portfolio
    {
        $portfolio = new Portfolio();
        $portfolio->user_id = $userId;
        $portfolio->name = $name;
        $portfolio->exchange = $exchange;
        if ($portfolio->save()) {
            return $portfolio;
        }
        throw new SaveException();
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

    function delete($portfolioId): ?bool
    {
        try {
            $portfolio = $this->getById($portfolioId);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException();
        }
        return $portfolio->delete();
    }
}
