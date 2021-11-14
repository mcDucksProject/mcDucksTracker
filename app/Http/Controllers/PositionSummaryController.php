<?php

namespace App\Http\Controllers;

use App\Http\Services\PositionService;
use App\Http\Services\Tracker\PositionSummaryService;
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
        return new JsonResponse($positionSummary);
    }
}
