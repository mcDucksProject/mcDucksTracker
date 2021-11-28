<?php

namespace App\Http\Controllers;

use App\Exceptions\DeleteException;
use App\Exceptions\SaveException;
use App\Http\Services\HistoricalPriceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;

class HistoricalPriceController extends Controller
{
    private HistoricalPriceService $historicalPriceService;

    function __construct(HistoricalPriceService $historicalPriceService)
    {
        $this->historicalPriceService = $historicalPriceService;
    }

    function create(Request $request): JsonResponse
    {
        $params = $request->validate([
            'pair_id' => 'required',
            'date' => 'required',
            'price' => 'required'
        ]);
        try {
            $historicalPrice = $this->historicalPriceService->create(
                $params['pair_id'],
                $params['date'],
                $params['price']
            );
        } catch (SaveException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse($historicalPrice);
    }

    function delete($id): JsonResponse
    {
        try {
            $this->historicalPriceService->delete($id);
        } catch (DeleteException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    function findByPair($pairId): JsonResponse
    {

        $historicalPrices = $this->historicalPriceService->findByPair($pairId);
        return new JsonResponse($historicalPrices);

    }

    function findByPairAndDate($pairId, $date): JsonResponse
    {
        $historicalPrices = $this->historicalPriceService->findByPairAndDate($pairId, $date);
        return new JsonResponse($historicalPrices);
    }

    function findByPairBetweenDates($pairId, $startDate, $endDate): JsonResponse
    {
        $historicalPrices = $this->historicalPriceService->findByPairBetweenDates(
            $pairId,
            $startDate,
            $endDate
        );
        return new JsonResponse($historicalPrices);
    }

    function updateHistoricalData(): JsonResponse
    {
        try {
            $this->historicalPriceService->updateHistoricalPrices();
        } catch (SaveException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse("Historical data updated successfully");
    }
}
