<?php

namespace App\Http\Services;

use App\Exceptions\DeleteException;
use App\Exceptions\SaveException;
use App\Models\Position;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderService
{
    /**
     * @throws SaveException
     */
    function create($holdingId, $userId, $quantity, $priceBTC, $priceUSDT, $date): Order
    {
        try {
            $order = new Order();
            $order->holding_id = Position::findOrFail($holdingId)->id;
            $order->user_id = $userId;
            $order->quantity = $quantity;
            $order->price_btc = $priceBTC;
            $order->price_usdt = $priceUSDT;
            $order->date = $date;
            $order->saveOrFail();
            return $order;
        } catch (\Throwable | ModelNotFoundException $e) {
            throw new SaveException($e->getMessage());
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

    function getByHolding($holdingId): Collection
    {
        return Order::whereHoldingId($holdingId)->get();
    }
}
