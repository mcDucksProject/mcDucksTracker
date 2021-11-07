<?php

namespace App\Http\Services;

use App\Exceptions\DeleteException;
use App\Exceptions\InvalidPairException;
use App\Exceptions\SaveException;
use App\Models\Order;
use App\Models\OrderPrice;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class OrderPriceService
{
    private PairService $pairService;

    function __construct(PairService $pairService)
    {

        $this->pairService = $pairService;
    }

    /**
     * @throws SaveException
     */
    function create(Order $order, $userId, $pairId, $price,$autoCalculated = false): OrderPrice
    {
        try {
            $pair = $this->pairService->getById($pairId);
            if ($order->position->token_id != $pair->base_id) {
                throw new InvalidPairException("Pair is not valid for this order");
            }
            $orderPrice = $this->createOrderPrice($order->id, $userId, $pairId, $price,$autoCalculated);
        } catch (ModelNotFoundException | InvalidPairException | Throwable $e) {
            throw new SaveException($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
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
        } catch (ModelNotFoundException | Throwable $e) {
            throw new DeleteException();
        }
    }

    function getByOrder($orderId): Collection
    {
        return OrderPrice::whereOrderId($orderId)->get();
    }

    /**
     * @throws Throwable
     */
    private function createOrderPrice($orderId, $userId, $pairId, $price,$autoCalculated): OrderPrice
    {
        $orderPrice = new OrderPrice();
        $orderPrice->order_id = $orderId;
        $orderPrice->user_id = $userId;
        $orderPrice->pair_id = $pairId;
        $orderPrice->price = $price;
        $orderPrice->auto_calculated = $autoCalculated;
        $orderPrice->saveOrFail();
        return $orderPrice;
    }

}
