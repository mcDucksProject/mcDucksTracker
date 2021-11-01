<?php

namespace App\Http\Controllers;

use App\Exceptions\DeleteException;
use App\Exceptions\SaveException;
use App\Http\Services\PairService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PairController extends Controller
{
    private PairService $pairService;

    function __construct(PairService $pairService)
    {
        $this->pairService = $pairService;
    }

    function create(int $baseId, int $quoteId): JsonResponse
    {
        try {
            $pair = $this->pairService->create($baseId, $quoteId);
        } catch (SaveException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($pair);
    }

    function delete($id): JsonResponse
    {
        try {
            $this->pairService->delete($id);
        } catch (DeleteException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    function getById($id): JsonResponse
    {
        try {
            $this->pairService->getById($id);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        }
    }

    function getByBaseId($id): JsonResponse
    {
        $pairs = $this->pairService->getByBaseId($id);
        return new JsonResponse($pairs);
    }

    function getByQuoteId($id): JsonResponse
    {
        $pairs = $this->pairService->getByQuoteId($id);
        return new JsonResponse($pairs);
    }

    function getByBaseIdAndQuoteId($baseId, $quoteId): JsonResponse
    {
        try {
            $pair = $this->pairService->getByBaseIdAndQuoteId($baseId, $quoteId);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($pair);
    }
}
