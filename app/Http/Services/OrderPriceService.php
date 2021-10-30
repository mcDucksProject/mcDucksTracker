<?php

namespace App\Http\Services;

use App\Exceptions\DeleteException;
use App\Exceptions\SaveException;
use App\Models\OrderPrice;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderPriceService
{
    /**
     * @throws SaveException
     */
    function create($orderId, $userId, $pairId, $price): OrderPrice
    {
        try {
            $orderPrice = new OrderPrice();
            $orderPrice->order_id = $orderId;
            $orderPrice->user_id = $userId;
            $orderPrice->pair_id = $pairId;
            $orderPrice->price = $price;
            $orderPrice->saveOrFail();
        } catch (\Throwable $e) {
            throw new SaveException();
        }
        return $orderPrice;
    }

    /**
     * @throws DeleteException
     */
    function delete($orderPriceId)
    {
        try {
            OrderPrice::findOrFail($orderPriceId)->deleteOrFail();
        } catch (ModelNotFoundException | \Throwable $e) {
            throw new DeleteException();
        }
    }

    function getByOrder($orderId): Collection
    {
        return OrderPrice::whereOrderId($orderId)->get();
    }

}
