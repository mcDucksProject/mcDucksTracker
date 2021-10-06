<?php

namespace App\Http\Controllers;

use App\Exceptions\SaveException;
use App\Http\Services\HoldingService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HoldingController extends Controller
{
    private HoldingService $holdingService;

    public function __construct(HoldingService $holdingService)
    {
        $this->holdingService = $holdingService;
    }

    function create(Request $request): JsonResponse
    {
        $request->validate([
            'portfolio_id' => 'required',
            'symbol' => 'required',
            'expected_sell' => 'required'
        ]);
        try {
            $holding = $this->holdingService->create(
                $request->get('portfolio_id'),
                $request->get('symbol'),
                $request->get('expected_sell')
            );
        } catch (SaveException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($holding);
    }

    function update(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required',
            'expected_sell' => 'required'
        ]);
        try {
            $holding = $this->holdingService->update($request->get('id'), $request->get('expected_sell'));
        } catch (SaveException | ModelNotFoundException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($holding, Response::HTTP_OK);
    }

    function getById(int $id): JsonResponse
    {
        try {
            $holding = $this->holdingService->getById($id);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($holding, Response::HTTP_OK);
    }

    function getByPortfolio(int $portfolioId): JsonResponse
    {
        try {
            $holdings = $this->holdingService->getByPortfolio($portfolioId);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($holdings, Response::HTTP_OK);
    }

    function getByUser(): JsonResponse
    {
        try {
            $holdings = $this->holdingService->getByUser(\Auth::id());
        } catch (ModelNotFoundException $e) {
            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($holdings, Response::HTTP_OK);
    }
}
