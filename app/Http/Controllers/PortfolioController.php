<?php

namespace App\Http\Controllers;

use App\Exceptions\SaveException;
use App\Http\Services\PortfolioService;
use App\Models\Exchange;
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
        $params = $request->validate([
            'name' => 'required',
            'exchange' => 'required_if:exchange_id,null',
            'exchange_id' => 'numeric|required_if:exchange,null'
        ]);
        if (key_exists('exchange', $params)) {
            $exchange = Exchange::whereName($params['exchange'])->firstOrFail();
            $exchange_id = $exchange->id;
        } else {
            $exchange_id = $params['exchange_id'];
        }
        try {
            $portfolio = $this->portfolioService->create(
                $params['name'],
                Auth::id(),
                $exchange_id);
        } catch (SaveException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($portfolio, Response::HTTP_CREATED);
    }

    function update(Request $request): JsonResponse
    {
        $params = $request->validate([
            'id' => 'bail|required',
            'name' => 'required',
        ]);
        try {
            $portfolio = $this->portfolioService->update(
                $params['id'],
                $params['name']);
        } catch (SaveException | ModelNotFoundException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($portfolio, Response::HTTP_OK);
    }

    function getById(int $id): JsonResponse
    {
        try {
            $portfolio = $this->portfolioService->getById($id);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse($portfolio, Response::HTTP_OK);
    }

    function getByUser(): JsonResponse
    {
        try {
            $portfolios = $this->portfolioService->getByUserId(Auth::id());
        } catch (ModelNotFoundException $e) {
            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($portfolios, Response::HTTP_OK);
    }

    function getByExchange(int $exchange_id): JsonResponse
    {
        try {
            $portfolios = $this->portfolioService->getByExchange($exchange_id);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($portfolios, Response::HTTP_OK);
    }
}
