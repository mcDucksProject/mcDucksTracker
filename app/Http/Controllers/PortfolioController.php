<?php

namespace App\Http\Controllers;

use App\Exceptions\DeleteException;
use App\Exceptions\SaveException;
use App\Http\Services\ExchangeService;
use App\Http\Services\PortfolioService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PortfolioController extends Controller
{
    private PortfolioService $portfolioService;
    private ExchangeService $exchangeService;

    public function __construct(PortfolioService $portfolioService, ExchangeService $exchangeService)
    {
        $this->portfolioService = $portfolioService;
        $this->exchangeService = $exchangeService;
    }

    function create(Request $request): JsonResponse
    {
        $params = $request->validate([
            'name' => 'required',
            'exchange.name' => 'required_if:exchange.id,null',
            'exchange.id' => 'numeric|required_if:exchange.name,null'
        ]);
        try {
            $portfolio = $this->portfolioService->create(
                $params['name'],
                Auth::id(),
                key_exists('id', $params['exchange']) ? $params['exchange']['id'] : '',
                key_exists('name', $params['exchange']) ? $params['exchange']['name'] : ''
            );
        } catch (ModelNotFoundException | SaveException $e) {
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

    function delete($id): JsonResponse
    {
        try {
            $this->portfolioService->delete($id);
        } catch (DeleteException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse('', Response::HTTP_NO_CONTENT);
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
