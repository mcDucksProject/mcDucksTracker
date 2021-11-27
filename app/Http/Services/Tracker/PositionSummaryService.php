<?php

namespace App\Http\Services\Tracker;

use App\Http\Services\TickerService;
use App\Models\Order;
use App\Models\OrderPrice;
use App\Models\Pair;
use App\Models\Position;
use App\Models\Tracker\PositionQuoteSummary;
use App\Models\Tracker\PositionSummary;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PositionSummaryService
{
    const SECTION_SEPARATOR = "---------------";
    private TickerService $tickerService;

    function __construct(TickerService $tickerService)
    {
        $this->tickerService = $tickerService;
    }

    public function getPositionSummaryAsArray(Position $position): array
    {
        $positionSummary = $this->calculatePositionSummary($position);
        return $this->formatPositionSummaryAsArray($positionSummary);
    }

    public function getPositionSummaryAsString(Position $position): string
    {
        $positionSummary = $this->calculatePositionSummary($position);
        return $this->formatPositionSummaryAsString($positionSummary);
    }

    public function calculatePositionSummary(Position $position): PositionSummary
    {
        $orders = $position->orders();
        $pairs = $position->token->pairs;
        $buyOrders = $orders->where('type', '=', 'buy')->with('Prices')->get();
        /** @TODO calculate sell orders */
        $totalQuantity = $buyOrders->sum('quantity');
        $firstBuy = $buyOrders->sortBy('order_date')->get(0)->order_date;
        $quotesSummary = $pairs->map(function (Pair $pair) use ($totalQuantity) {
            return $this->initializeCuoteSummary($pair, $totalQuantity);
        });

        $buyOrders->reduce(function (Collection $quotesSummary, Order $order) {
            return $this->calculateQuoteSummaryPrice($order, $quotesSummary);
        }, $quotesSummary);
        $positionSummary = new PositionSummary();
        $positionSummary
            ->setBase($position->token)
            ->setStartDate($firstBuy)
            ->setQuantity($totalQuantity)
            ->setQuotesSummary($quotesSummary);

        return $positionSummary;
    }

    private function initializeCuoteSummary(Pair $pair, $totalQuantity): PositionQuoteSummary
    {
        $price = $this->tickerService->getPriceByPair($pair);
        $quoteSummary = new PositionQuoteSummary();
        $quoteSummary
            ->setPair($pair)
            ->setQuantity($totalQuantity)
            ->setLastTickerUpdate($price->ticker_date)
            ->setCurrentPrice($price->price);
        return $quoteSummary;
    }

    private function calculateQuoteSummaryPrice(Order $order, Collection $quotesSummary): Collection
    {
        $quantity = $order->quantity;
        $prices = $order->prices;
        return $quotesSummary->map(function (PositionQuoteSummary $quoteSummary) use ($quantity, $prices) {
            /** @var OrderPrice $price */
            $price = $prices->firstWhere('pair_id', '=', $quoteSummary->getPair()->id);
            return $quoteSummary->addInvested($price->price * $quantity);
        });
    }

    private function formatPositionSummaryAsArray(PositionSummary $positionSummary): array
    {
        return [
            'base' => $positionSummary->getBase()->name,
            'quantity' => $positionSummary->getQuantity(),
            'start_date' => $positionSummary->getStartDate()->format("d-m-Y H:i:s"),
            'quotes' => $this->formatQuotesSummaryAsArray($positionSummary->getQuotesSummary())
        ];
    }

    private function formatQuotesSummaryAsArray(Collection $quotesSummary): Collection
    {
        return $quotesSummary->map(function (PositionQuoteSummary $quoteSummary) {
            return [
                'quote' => $quoteSummary->getPair()->quote->name,
                'invested' => $quoteSummary->getInvested(),
                'average_buy' => number_format($quoteSummary->getAverageBuy(), 10, '.', ''),
                'actual_price' => number_format($quoteSummary->getCurrentPrice(), 10, '.', ''),
                'pnl_price' => $quoteSummary->getPnlInQuoteValue(),
                'pnl_percentage' => round($quoteSummary->getPnlInPercentage(true), 2) . '%',
                'last_ticker_update' => $quoteSummary->getLastTickerUpdate()->format("d-m-Y H:i:s")
            ];
        });
    }

    private function formatPositionSummaryAsString(PositionSummary $positionSummary): string
    {
        $now = new Carbon();
        return "
        {$positionSummary->getBase()->name} | {$positionSummary->getQuantity()}
        {$positionSummary->getStartDate()->format("d-m-Y H:i")} " .
            " ({$now->diffInDays($positionSummary->getStartDate(),true)} days)
            " . self::SECTION_SEPARATOR
            . "{$this->formatQuotesSummaryAsString($positionSummary->getQuotesSummary())}
        ";
    }

    private function formatQuotesSummaryAsString(Collection $quotesSummary): string
    {
        return $quotesSummary->map(function (PositionQuoteSummary $quoteSummary) {
            return "
            {$quoteSummary->getPair()->quote->name} | {$quoteSummary->getInvested()}
            [ACTIVE PNL] {$this->getColorEmoji($quoteSummary->getPnlInQuoteValue() > 0)}
            {$quoteSummary->getPnlInPercentage(true)}% | {$quoteSummary->getPnlInQuoteValue()}
            [AVERAGE BUY]
            {$quoteSummary->getAverageBuy()}
            [CURRENT PRICE]
            {$quoteSummary->getCurrentPrice()}
            ";
        })->implode(self::SECTION_SEPARATOR);
    }

    private function getColorEmoji(bool $condition): string
    {
        return $condition ? "🟩" : "🟥";
    }
}
