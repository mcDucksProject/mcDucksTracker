<?php

namespace App\Http\Controllers;

use App\Exceptions\SaveException;
use App\Http\Services\PortfolioService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PortfolioController
{
    private PortfolioService $portfolioService;

    public function __construct(PortfolioService $portfolioService)
    {
        $this->portfolioService = $portfolioService;
    }

    function create(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required'
        ]);
        try {
            $portfolio = $this->portfolioService->create($request->get('name'), Auth::id());
        } catch (SaveException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($portfolio, Response::HTTP_CREATED);
    }

    function update(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'bail|required',
            'name' => 'required',
        ]);
        try {
            $portfolio = $this->portfolioService->update(
                $request->get('id'),
                $request->get('name'),
                Auth::id());
        } catch (SaveException | ModelNotFoundException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($portfolio, Response::HTTP_OK);
    }

    function getPortfolio(int $id): JsonResponse
    {
        try {
            $portfolio = $this->portfolioService->getById($id);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse($portfolio, Response::HTTP_FOUND);
    }

    function getUserPortfolios(): JsonResponse
    {
        try {
            $portfolios = $this->portfolioService->getByUserId(Auth::id());
        } catch (ModelNotFoundException $e) {
            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($portfolios, Response::HTTP_FOUND);
    }
}
