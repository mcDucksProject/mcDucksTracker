<?php

namespace App\Http\Services;

use App\Exceptions\DeleteException;
use App\Exceptions\SaveException;
use App\Models\Pair;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class PairService
{
    /**
     * @throws SaveException
     */
    function create($baseId, $quoteId): Pair
    {
        try {
            $pair = new Pair();
            $pair->base_id = $baseId;
            $pair->quote_id = $quoteId;
            $pair->saveOrFail();
        } catch (Throwable $e) {
            throw new SaveException($e->getMessage());
        }
        return $pair;
    }

    /**
     * @throws DeleteException
     */
    function delete($pairId)
    {
        try {
            Pair::findOrFail($pairId)->deleteOrFail();
        } catch (ModelNotFoundException | Throwable $e) {
            throw new DeleteException();
        }
    }

    function getById($pairId): Pair
    {
        return Pair::findOrFail($pairId);
    }

    function getByBaseId($baseId): Collection
    {
        return Pair::whereBaseId($baseId)->with(["base", "quote"])->get();
    }

    function getByQuoteId($quoteId): Collection
    {
        return Pair::whereQuoteId($quoteId)->with(["base", "quote"])->get();
    }

    /**
     * @throws ModelNotFoundException
     */
    function getByBaseIdAndQuoteId($baseId, $quoteId): Pair
    {
        return Pair::where('base_id', '=', $baseId)
            ->where('quote_id', '=', $quoteId)
            ->with(["base", "quote"])
            ->firstOrFail();
    }

    function getAll(): Collection
    {
        return Pair::with(["base", "quote"])->get();
    }
}
