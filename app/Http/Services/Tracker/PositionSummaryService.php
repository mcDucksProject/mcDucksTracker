<?php

namespace App\Http\Services\Tracker;

use App\Http\Services\TickerService;
use App\Models\Order;
use App\Models\OrderPrice;
use App\Models\Pair;
use App\Models\Position;
use App\Models\Tracker\PositionSummary;
use App\Models\Tracker\QuoteSummary;
use Illuminate\Support\Collection;

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
        $quotesSummary = $pairs->map(function (Pair $pair) use ($totalQuantity) {
            $price = $this->tickerService->getPriceByPair($pair);
            $quoteSummary = new QuoteSummary();
            $quoteSummary
                ->setPair($pair)
                ->setQuantity($totalQuantity)
                ->setLastTickerUpdate($price->ticker_date)
                ->setActualPrice($price->price);
            return $quoteSummary;
        });

        $buyOrders->reduce(function (Collection $quotesSummary, Order $order) {
            $quantity = $order->quantity;
            $prices = $order->prices;
            return $quotesSummary->map(function (QuoteSummary $quoteSummary) use ($quantity, $prices) {
                /** @var OrderPrice $price */
                $price = $prices->firstWhere('pair_id', '=', $quoteSummary->getPair()->id);
                return $quoteSummary->addInvested($price->price * $quantity);
            });
        }, $quotesSummary);
        $positionSummary = new PositionSummary();
        $positionSummary
            ->setBase($position->token)
            ->setStartDate($firstBuy)
            ->setQuantity($totalQuantity)
            ->setQuotesSummary($quotesSummary);

        return $positionSummary;
    }

    private function calculateAverageBuy(Collection $buyOrders): void
    {

    }
}
