<?php

namespace App\Http\Services\Tracker;

use App\Http\Services\TickerService;
use App\Models\Order;
use App\Models\OrderPrice;
use App\Models\Pair;
use App\Models\Position;
use App\Models\Tracker\PositionSummary;
use App\Models\Tracker\QuoteSummary;
use Ramsey\Collection\Collection;

class PositionSummaryService
{
    private TickerService $tickerService;

    function __construct(TickerService $tickerService)
    {
        $this->tickerService = $tickerService;
    }

    function calculatePositionStatus(Position $position): PositionSummary
    {
        $orders = $position->orders();
        $pairs = $position->token->pairs;
        $buyOrders = $orders->where('type', '=', 'buy')->with('Prices')->get();
        /** @TODO calculate sell orders */

        $totalQuantity = $buyOrders->sum('quantity');
        $firstBuy = $buyOrders->sortBy('order_date')->get(0)->order_date;
        $quotesSummary = $pairs->map(function (Pair $pair) {
            $quoteSummary = new QuoteSummary();
            $quoteSummary->setPair($pair);
            return $quoteSummary;
        });
        $buyOrders->reduce(function (Collection $quoteSummary, Order $order) {
            $quantity = $order->quantity;
            $prices = $order->prices;
            return $quoteSummary->map(function (QuoteSummary $quoteSummary) use ($quantity, $prices) {
                /** @var OrderPrice $price */
                $price = $prices->firstWhere('pair_id', '=', $quoteSummary->getPair()->id);
                return $quoteSummary->addInvested($price->price * $quantity);
            });
        }, $quotesSummary);
        $buyOrders->flatMap(function (Order $item) {
            $quantity = $item->quantity;
            return $item->prices->map(function (OrderPrice $price) use ($quantity) {
                return [
                    'quantity' => $quantity,
                    'pair' => $price->pair,
                    'price' => $price->price,
                ];
            });
        });
        /*$buyOrders->each(function (Order $order) use (&$quotesSummary, &$firstBuy) {
            $quantity = $order->quantity;
            $firstBuy = ($order->order_date->diffInMicroseconds($firstBuy) > 0) ? $firstBuy : $order->order_date;
            $order->prices->each(function (OrderPrice $orderPrice) use (&$quotesSummary, $quantity) {
                $quote = $orderPrice->pair->quote->name;
                $invested = $quantity * $orderPrice->price;
                $quoteSummary = $quotesSummary->where('')
                $investedByQuote[$quote] = array_key_exists($quote, $investedByQuote)
                    ? $investedByQuote[$quote] + $invested
                    : $invested;
            });
        });*/
        $positionSummary = new PositionSummary();
        $positionSummary->setBase($position->token);
        $positionSummary->setStartDate($firstBuy);
        $positionSummary->setQuantity($totalQuantity);
        foreach ($investedByQuote as $quote => $invested) {
            $quotesSumary = new QuoteSummary();
            //$quoteSummary->setQuote();
            $quotesSumary->setInvested($invested);
            $quotesSumary->setAverageBuy($invested / $totalQuantity);
            //$quoteSummary->setActualPrice($this->tickerService->)
        }
        return $positionSummary;
    }

    private function calculateAverageBuy(Collection $buyOrders): void
    {

    }
}
