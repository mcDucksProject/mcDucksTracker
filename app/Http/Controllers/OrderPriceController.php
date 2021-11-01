<?php

namespace App\Http\Controllers;

use App\Exceptions\DeleteException;
use App\Exceptions\SaveException;
use App\Http\Services\OrderPriceService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderPriceController extends Controller
{
    private OrderPriceService $orderPriceService;

    function __construct(OrderPriceService $orderPriceService)
    {
        $this->orderPriceService = $orderPriceService;
    }

    function create($positionId, $orderId, Request $request): JsonResponse
    {
        $params = $request->validate([
            'pair_id' => 'required',
            'price' => 'required'
        ]);
        try {
            $orderPrice = $this->orderPriceService->create(
                $orderId,
                \Auth::id(),
                $params['pair_id'],
                $params['price']
            );
        } catch (SaveException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($orderPrice);
    }

    function delete(Request $request): JsonResponse
    {
        $params = $request->validate([
            'order_price_id' => 'required'
        ]);
        try {
            $this->orderPriceService->delete($params['order_price_id']);
        } catch (DeleteException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    function getByOrder(int $orderId): JsonResponse
    {
        try {
            $orderPrices = $this->orderPriceService->getByOrder($orderId);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse("", Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($orderPrices, Response::HTTP_OK);
    }
}
