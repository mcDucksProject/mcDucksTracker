<?php

namespace App\Http\Services;

use App\Exceptions\CalculatePricesException;
use App\Exceptions\DeleteException;
use App\Exceptions\SaveException;
use App\Models\HistoricalPrice;
use App\Models\Order;
use App\Models\Pair;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use LogicException;
use Throwable;

class OrderService
{
    private HistoricalPriceService $historicalPriceService;
    private OrderPriceService $orderPriceService;

    function __construct(HistoricalPriceService $historicalPriceService, OrderPriceService $orderPriceService)
    {
        $this->historicalPriceService = $historicalPriceService;
        $this->orderPriceService = $orderPriceService;
    }

    /**
     * @throws SaveException
     */
    function create(
        int $userId,
        Position $position,
        float $quantity,
        string $status,
        string $type,
        Carbon $date,
        Collection $prices,
        bool $calculateOtherPairs = false
    ): Order {
        try {
            $order = new Order();
            $order->position_id = $position->id;
            $order->user_id = $userId;
            $order->quantity = $quantity;
            $order->order_date = $date;
            $order->status = $status;
            $order->type = $type;
            $order->saveOrFail();
            $order = $order->refresh();
            if ($prices->count() > 0) {
                $this->calculatePrices($order, $date, $prices, $calculateOtherPairs);
            }
            return $order;
        } catch (Throwable | ModelNotFoundException $e) {
            throw new SaveException();
        }
    }

    /**
     * @throws CalculatePricesException
     */
    private function calculatePrices(
        Order $order,
        Carbon $date,
        Collection $prices,
        bool $calculateOtherPairs = false
    ): void {
        $pairs = $order->position->token->pairs;

        $remainingPairs = $pairs->filter(function (Pair $pair) use ($prices, $order) {
            $price = $prices->where('quote', '=', $pair->quote->name)->first();
            if (!$price) {
                return true;
            }
            $this->orderPriceService->create(
                $order,
                $order->user_id,
                $pair->id,
                $price['price']
            );
            return false;
        });
        if ($calculateOtherPairs && $remainingPairs->count() > 0) {
            $remainingPairs->each(function (Pair $pair) use ($date, $order) {
                /** @var HistoricalPrice $price */
                $price = $this->historicalPriceService->findByPairAndDate(
                    $pair->id,
                    $date
                )->first();
                try {
                    $this->orderPriceService->create(
                        $order,
                        $order->user_id,
                        $pair->id,
                        $price->price,
                        true
                    );
                } catch (SaveException $e) {
                    throw new CalculatePricesException();
                }
            });
        }
    }

    /**
     * @throws SaveException
     */
    function update(
        int $orderId,
        float $quantity = 0.0,
        string $status = "",
        string $type = "",
        Carbon $date = null
    ): Order {
        try {
            $order = Order::findOrFail($orderId);
            if ($quantity != 0.0) {
                $order->quantity = $quantity;
            }
            if ($date != null) {
                $order->order_date = $date;
            }
            if ($status != "") {
                $order->status = $status;
            }
            if ($type != "") {
                $order->type = $type;
            }
            $order->saveOrFail();

        } catch (Throwable $e) {
            throw new SaveException();
        }
        return $order;
    }

    /**
     * @throws DeleteException
     */
    function delete(int $orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            $order->delete();
        } catch (ModelNotFoundException | LogicException $e) {
            throw new DeleteException();
        }

    }

    /**
     * @throws ModelNotFoundException
     */
    function getById(int $orderId): Order
    {
        return Order::whereId($orderId)->with("position", "position.token", "prices")->firstOrFail();
    }

    function getByPositionId(int $positionId): Collection
    {
        return Order::wherePositionId($positionId)->with("position", "position.token", "prices")->get();
    }
}
