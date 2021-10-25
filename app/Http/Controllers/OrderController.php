<?php

namespace App\Http\Controllers;

use App\Exceptions\DeleteException;
use App\Exceptions\SaveException;
use App\Http\Services\OrderService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    function create(Request $request): JsonResponse
    {
        $params = $request->validate([
            'position_id' => 'required',
            'quantity' => 'required',
            'date' => 'required',
            'status' => 'in:filled,open',
            'type' => 'in:buy,sell'
        ]);
        try {
            $order = $this->orderService->create(
                $request->get('holding_id'),
                \Auth::id(),
                $request->get('quantity'),
                $request->get('date')
            );
        } catch (SaveException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($order, Response::HTTP_CREATED);
    }

    function update(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required'
        ]);
        try {
            $order = $this->orderService->update(
                $request->get('order_id'),
                $request->get('quantity') ?? '',
                $request->get('price') ?? '',
                $request->get('date') ?? ''
            );
        } catch (SaveException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($order, Response::HTTP_OK);
    }

    function delete(int $id): JsonResponse
    {
        try {
            $this->orderService->delete($id);
        } catch (DeleteException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }


    function getById(int $id): JsonResponse
    {
        try {
            $order = $this->orderService->getById($id);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($order, Response::HTTP_OK);
    }

    function getByHolding(int $holdingId): JsonResponse
    {
        try {
            $orders = $this->orderService->getByHolding($holdingId);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($orders, Response::HTTP_OK);
    }
}
