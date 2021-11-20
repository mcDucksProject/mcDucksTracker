<?php

namespace App\Http\Controllers;

use App\Http\Services\PositionService;
use App\Http\Services\Tracker\PositionSummaryService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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

    function getPositionSummary(int $id, string $format = "array"): Response
    {
        try {
            if ($format == "array") {
                $result = $this->positionSummaryService->getPositionSummaryAsArray($this->positionService->getById($id));
                return new JsonResponse($result);
            } else {
                $result = $this->positionSummaryService->getPositionSummaryAsString(
                    $this->positionService->getById($id)
                );
                return new Response($result);
            }

        } catch (ModelNotFoundException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }


}
