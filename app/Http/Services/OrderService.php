<?php

namespace App\Http\Services;

use App\Exceptions\DeleteException;
use App\Exceptions\SaveException;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderService
{
    /**
     * @throws SaveException
     */
    function create($positionId, $userId, $quantity, $price, $date): Order
    {
        $order = new Order([
            'position_id' => $positionId,
            'user_id' => $userId,
            'quantity' => $quantity,
            'price' => $price,
            'date' => $date
        ]);
        try {
            $order->saveOrFail();
        } catch (\Throwable $e) {
            throw new SaveException();
        }
    }

    /**
     * @throws SaveException
     */
    function update($orderId, $quantity = "", $price = "", $date = ""): Order
    {
        try {
            $order = Order::findOrFail($orderId);
            if ($quantity != "") {
                $order->quantity = $quantity;
            }
            if ($price != "") {
                $order->price = $price;
            }
            if ($date != "") {
                $order->date = $date;
            }
            $order->saveOrFail();

        } catch (\Throwable $e) {
            throw new SaveException();
        }
        return $order;
    }

    /**
     * @throws DeleteException
     */
    function delete($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            $order->delete();
        } catch (ModelNotFoundException | \LogicException $e) {
            throw new DeleteException();
        }

    }

    /**
     * @throws ModelNotFoundException
     */
    function getById($orderId): Order
    {
        return Order::findOrFail($orderId);
    }

    function getByPosition($positionId): Collection
    {
        return Order::wherePositionId($positionId)->get();
    }
}
