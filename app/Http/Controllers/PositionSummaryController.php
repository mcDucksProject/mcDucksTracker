<?php

namespace App\Http\Controllers;

use App\Http\Services\PositionService;
use App\Http\Services\Tracker\PositionSummaryService;
use App\Models\Tracker\QuoteSummary;
use Illuminate\Http\JsonResponse;

class PositionSummaryController
{
    private PositionSummaryService $positionSummaryService;
    private PositionService $positionService;

    function __construct(
        PositionSummaryService $positionSummaryService,
        PositionService $positionService
    ) {
        $this->positionSummaryService = $positionSummaryService;
        $this->positionService = $positionService;
    }

    function getPositionSummary(int $id): JsonResponse
    {
        $positionSummary = $this->positionSummaryService->calculatePositionStatus($this->positionService->getById($id));
        $result = [
            'base' => $positionSummary->getBase()->name,
            'quantity' => $positionSummary->getQuantity(),
            'start_date' => $positionSummary->getStartDate()->format("d-m-Y H:i:s"),
            'quotes' => $positionSummary->getQuotesSummary()->map(function (QuoteSummary $quoteSummary) {
                return [
                    'quote' => $quoteSummary->getPair()->quote->name,
                    'invested' => $quoteSummary->getInvested(),
                    'average_buy' => number_format($quoteSummary->getAverageBuy(), 10, '.', ''),
                    'actual_price' => number_format($quoteSummary->getActualPrice(), 10, '.', ''),
                    'pnl_price' => $quoteSummary->getPnLInPrice(),
                    'pnl_percentage' => round($quoteSummary->getPnLInPercentage(true), 2) . '%',
                    'last_ticker_update' => $quoteSummary->getLastTickerUpdate()->format("d-m-Y H:i:s")
                ];
            })
        ];
        return new JsonResponse($result);
    }
}
