<?php

namespace App\Http\Services;

use App\Exceptions\DeleteException;
use App\Exceptions\SaveException;
use App\Models\Order;
use App\Models\OrderPrice;
use App\Models\Pair;
use App\Models\Position;
use App\Models\Token;
use Carbon\Carbon;
use Date;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use LogicException;
use Throwable;

class OrderService
{
    private HistoricalPriceService $historicalPriceService;

    function __construct(HistoricalPriceService $historicalPriceService)
    {
        $this->historicalPriceService = $historicalPriceService;
    }

    /**
     * @throws SaveException
     */
    function create(
        int $userId,
        int $positionId,
        float $quantity,
        string $status,
        string $type,
        Carbon $date,
        Collection $prices,
        $calculateOtherPairs = false
    ): Order {
        try {
            /** @var Position $position */
            $position = Position::with("token")->findOrFail($positionId);
            $order = new Order();
            $order->position_id = $position->id;
            $order->user_id = $userId;
            $order->quantity = $quantity;
            $order->date = $date;
            $order->status = $status;
            $order->type = $type;
            $order->saveOrFail();
            if ($prices->count() > 0) {
                $this->calculatePrices($date, $position->token, $prices, $calculateOtherPairs);
            }
            return $order;
        } catch (Throwable | ModelNotFoundException $e) {
            throw new SaveException();
        }
    }

    function calculatePrices(Carbon $date, Token $token,Collection $prices,$calculateOtherPairs): OrderPrice
    {
        $pairs = $token->pairs;

        $prices->filter(function($price) use ($pairs){
            /** @var Pair $pair */
            //$price['quote'] =
        });
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

        } catch (Throwable $e) {
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
        } catch (ModelNotFoundException | LogicException $e) {
            throw new DeleteException();
        }

    }

    /**
     * @throws ModelNotFoundException
     */
    function getById($orderId): Order
    {
        return Order::whereId($orderId)->with("position", "position.token", "prices")->firstOrFail();
    }

    function getByPositionId($positionId): Collection
    {
        return Order::wherePositionId($positionId)->with("position", "position.token", "prices")->get();
    }
}
