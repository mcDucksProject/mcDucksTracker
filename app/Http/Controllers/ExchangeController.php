<?php

namespace App\Http\Controllers;

use App\Exceptions\DeleteException;
use App\Exceptions\SaveException;
use App\Exceptions\UpdateException;
use App\Http\Services\ExchangeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExchangeController extends Controller
{
    private ExchangeService $exchangeService;

    function __construct(ExchangeService $exchangeService)
    {
        $this->exchangeService = $exchangeService;
    }

    function create(Request $request): JsonResponse
    {
        $params = $request->validate([
            'name' => 'required'
        ]);
        try {
            $exchange = $this->exchangeService->create($params['name']);
        } catch (SaveException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($exchange);
    }

    function update(Request $request): JsonResponse
    {
        $params = $request->validate([
            'id' => 'required',
            'name' => 'required'
        ]);
        try {
            $exchange = $this->exchangeService->update($params['id'], $params['name']);
        } catch (UpdateException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($exchange);
    }

    function delete(int $id): JsonResponse
    {
        try {
            $this->exchangeService->delete($id);
        } catch (DeleteException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse("", Response::HTTP_NO_CONTENT);
    }

    function get(): JsonResponse
    {

    }
}
