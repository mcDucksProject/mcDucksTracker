<?php

namespace App\Http\Services\Tracker;

use App\Exceptions\SummaryException;
use App\Http\Services\PortfolioService;
use App\Models\Portfolio;
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
    function getPortfolioSummaryAsString(Portfolio $portfolio): string
    {
        try {
            $positionSummaries = $portfolio->positions->map(function (Position $position) {
                return $this->positionSummaryService->getPositionSummaryAsString($position);
            });
            return $positionSummaries->implode('');
            /*            $quotesSummary = new Collection(PortfolioQuoteSummary::class);
                        $positionSummaries->each(function (PositionSummary $positionSummary) use ($quotesSummary) {
                            $positionSummary->getQuotesSummary()->each(
                                function (PositionQuoteSummary $quoteSummary) use ($quotesSummary) {
                                    //$quotesSummary->firstWhere("", "", $quoteSummary->getPair()->quote)
                                });
                        });*/
        } catch (ModelNotFoundException $e) {
            throw new SummaryException($e->getMessage());
        }
    }
}
