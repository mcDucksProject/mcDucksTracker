<?php

namespace App\Http\Services;

use App\Exceptions\DeleteException;
use App\Exceptions\SaveException;
use App\Models\Order;
use App\Models\Position;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderService
{
    /**
     * @throws SaveException
     */
    function create($userId, $positionId, $quantity, $status, $type, $date): Order
    {
        try {
            $order = new Order();
            $order->position_id = Position::findOrFail($positionId)->id;
            $order->user_id = $userId;
            $order->quantity = $quantity;
            $order->date = $date;
            $order->status = $status;
            $order->type = $type;
            $order->saveOrFail();
            return $order;
        } catch (\Throwable | ModelNotFoundException $e) {
            throw new SaveException();
        }
    }

    /**
     * @throws SaveException
     */
    function update(
        $orderId,
        $quantity = "",
        $status = "",
        $type = "",
        $date = ""
    ): Order {
        try {
            $order = Order::findOrFail($orderId);
            if ($quantity != "") {
                $order->quantity = $quantity;
            }
            if ($date != "") {
                $order->date = $date;
            }
            if ($status != "") {
                $order->status = $status;
            }
            if ($type != "") {
                $order->type = $type;
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

    function getByPositionId($holdingId): Collection
    {
        return Order::wherePositionId($holdingId)->get();
    }
}
