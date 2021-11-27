<?php

namespace App\Http\Controllers;

use App\Exceptions\SummaryException;
use App\Http\Services\PortfolioService;
use App\Http\Services\Tracker\PortfolioSummaryService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PortfolioSummaryController
{
    private PortfolioSummaryService $portfolioSummaryService;
    private PortfolioService $portfolioService;

    public function __construct(
        PortfolioSummaryService $portfolioSummaryService,
        PortfolioService $portfolioService
    ) {

        $this->portfolioSummaryService = $portfolioSummaryService;
        $this->portfolioService = $portfolioService;
    }

    function getPortfolioSummary(int $id, string $format = "string"): Response
    {
        try {

            $result = $this->portfolioSummaryService->getPortfolioSummaryAsString(
                $this->portfolioService->getById($id)
            );
            return new Response($result);

        } catch (SummaryException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}
