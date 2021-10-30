<?php

namespace App\Http\Controllers;

use App\Exceptions\SaveException;
use App\Exceptions\UpdateException;
use App\Http\Services\PositionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PositionController extends Controller
{
    private PositionService $positionService;

    public function __construct(PositionService $holdingService)
    {
        $this->positionService = $holdingService;
    }

    function create(Request $request): JsonResponse
    {
        $params = $request->validate([
            'token_id' => 'required',
            'portfolio_id' => 'required',
            'status' => 'in:open,closed'
        ]);
        try {
            $position = $this->positionService->create(
                $params['token_id'],
                \Auth::id(),
                $params['portfolio_id'],
                $params['status']
            );
        } catch (SaveException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($position);
    }

    function update(Request $request): JsonResponse
    {
        $params = $request->validate([
            'id' => 'required',
            'status' => 'required'
        ]);
        try {
            $position = $this->positionService->update(
                $params['holding_id'],
                $request->get('expected_sell'));
        } catch (UpdateException | ModelNotFoundException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($position, Response::HTTP_OK);
    }

    function getById(int $id): JsonResponse
    {
        try {
            $holding = $this->positionService->getById($id);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($holding, Response::HTTP_OK);
    }

    function getByPortfolio(int $portfolioId): JsonResponse
    {
        try {
            $holdings = $this->positionService->getByPortfolio($portfolioId);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($holdings, Response::HTTP_OK);
    }

    function getByUser(): JsonResponse
    {
        try {
            $holdings = $this->positionService->getByUser(\Auth::id());
        } catch (ModelNotFoundException $e) {
            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($holdings, Response::HTTP_OK);
    }
}
