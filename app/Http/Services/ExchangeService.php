<?php

namespace App\Http\Services;

use App\Exceptions\DeleteException;
use App\Exceptions\SaveException;
use App\Exceptions\UpdateException;
use App\Models\Exchange;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ExchangeService
{
    /**
     * @throws SaveException
     */
    function create($name): Exchange
    {
        $exchange = new Exchange();
        $exchange->name = $name;
        try {
            $exchange->saveOrFail();
        } catch (\Throwable $e) {
            throw new SaveException($e->getMessage());
        }
        return $exchange;
    }

    /**
     * @throws UpdateException
     */
    function update($id, $name): Exchange
    {
        try {
            $exchange = Exchange::findOrFail($id);
            $exchange->name = $name;
            $exchange->saveOrFail();
        } catch (ModelNotFoundException | \Throwable $e) {
            throw new UpdateException();
        }
        return $exchange;
    }

    /**
     * @throws DeleteException
     */
    function delete($id): Exchange
    {
        try {
            $exchange = Exchange::findOrFail($id);
            $exchange->deleteOrFail();
        } catch (ModelNotFoundException | \Throwable $e) {
            throw new DeleteException();
        }
    }

    function get(): Collection
    {
        return Exchange::all();
    }

    /**
     * @throws ModelNotFoundException
     */
    function getById($id): Collection
    {
        return Exchange::findOrFail($id);
    }
}
