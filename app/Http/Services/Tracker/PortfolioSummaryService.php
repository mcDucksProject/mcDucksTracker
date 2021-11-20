<?php

namespace App\Http\Services\Tracker;

use App\Exceptions\SummaryException;
use App\Http\Services\PortfolioService;
use App\Models\Position;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PortfolioSummaryService
{
    private PositionSummaryService $positionSummaryService;
    private PortfolioService $portfolioService;

    function __construct(PositionSummaryService $positionSummaryService, PortfolioService $portfolioService)
    {

        $this->positionSummaryService = $positionSummaryService;
        $this->portfolioService = $portfolioService;
    }

    /**
     * @throws SummaryException
     */
    function calculatePortfolioSummary(int $portfolioId)
    {
        try {
            $portfolio = $this->portfolioService->getById($portfolioId);
            $positionSummaries = $portfolio->positions->map(function (Position $position) {
                return $this->positionSummaryService->calculatePositionSummary($position);
            });

        } catch (ModelNotFoundException $e) {
            throw new SummaryException($e->getMessage());
        }
    }
}
