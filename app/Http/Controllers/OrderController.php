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
                \Auth::id(),
                $params['position_id'],
                $params['quantity'],
                $params['status'],
                $params['type'],
                $params['date']
            );
        } catch (SaveException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($order, Response::HTTP_CREATED);
    }

    function update(Request $request): JsonResponse
    {
        $params = $request->validate([
            'order_id' => 'required',
            'quantity' => '',
            'date' => '',
            'status' => '',
            'type' => ''
        ]);
        try {
            $order = $this->orderService->update(
                $params['order_id'],
                $params['quantity'] ?? "",
                $params['status'] ?? "",
                $params['type'] ?? "",
                $params['date'] ?? ""
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
        return new JsonResponse("", Response::HTTP_NO_CONTENT);
    }


    function getById(int $id): JsonResponse
    {
        try {
            $order = $this->orderService->getById($id);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse("", Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($order, Response::HTTP_OK);
    }

    function getByPosition(int $positionId): JsonResponse
    {
        try {
            $orders = $this->orderService->getByPositionId($positionId);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse("", Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($orders, Response::HTTP_OK);
    }
}
