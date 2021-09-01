<?php

namespace App\Http\Controllers;

use App\Exceptions\SaveException;
use App\Http\Services\PositionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PositionController extends Controller
{
    private PositionService $positionService;

    public function __construct(PositionService $positionService)
    {
        $this->positionService = $positionService;
    }

    function create(Request $request): JsonResponse
    {
        $request->validate([
            'portfolio_id' => 'required',
            'token' => 'required',
            'pair' => 'required',
            'expected_sell' => 'required'
        ]);
        try {
            $trade = $this->positionService->create(
                $request->get('portfolio_id'),
                $request->get('token'),
                $request->get('pair'),
                $request->get('expected_sell')
            );
        } catch (SaveException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($trade);
    }

    function update(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required',
            'expected_sell' => 'required'
        ]);
        try {
            $this->positionService->update($request->get('id'), $request->get('expected_sell'));
        } catch (SaveException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    function getById(int $id): JsonResponse
    {
        try {
            $position = $this->positionService->getById($id);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($position, Response::HTTP_OK);
    }

    function getByPortfolio(int $portfolioId): JsonResponse
    {
        try {
            $positions = $this->positionService->getByPortfolio($portfolioId);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($positions, Response::HTTP_OK);
    }
}
