<?php

namespace App\Http\Controllers;

use App\Exceptions\SaveException;
use App\Exceptions\UpdateException;
use App\Http\Services\OrderService;
use App\Http\Services\PositionService;
use App\Http\Services\TokenService;
use App\Models\Portfolio;
use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PositionController extends Controller
{
    private PositionService $positionService;
    private OrderService $orderService;
    private TokenService $tokenService;

    public function __construct(
        PositionService $positionService,
        OrderService $orderService,
        TokenService $tokenService
    ) {
        $this->positionService = $positionService;
        $this->orderService = $orderService;
        $this->tokenService = $tokenService;
    }

    function create(Request $request): JsonResponse
    {
        $params = $request->validate([
            'token.name' => 'required_if:token.id,null',
            'token.id' => 'required_if:token.name,null',
            'portfolio.id' => 'required',
            'status' => 'in:open,closed',
            'orders.*' => 'nullable|array:quantity,type,date,prices',
            'orders.*.prices.*' => 'nullable|array:quote,price'
        ]);
        try {
            $token = array_key_exists('id', $params['token'])
                ? $this->tokenService->getById($params['token']['id'])
                : $this->tokenService->getByName($params['token']['name']);
            $portfolio = Portfolio::findOrFail($params['portfolio']['id']);
            $position = $this->positionService->create(
                $token,
                Auth::id(),
                $portfolio,
                $params['status']
            );
            if (!is_null($params['orders']) && sizeof($params['orders']) > 0) {
                $orders = collect($params['orders']);
                $orders->each(function ($order) use ($position) {
                    $prices = collect($order['prices']);
                    $this->orderService->create(
                        Auth::id(),
                        $position,
                        $order['quantity'],
                        'filled',
                        $order['type'],
                        new Carbon($order['date']),
                        $prices,
                        true
                    );
                });
            }

        } catch (NotFoundHttpException|ModelNotFoundException|SaveException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $position->refresh();
        return new JsonResponse($position);
    }

    function update(Request $request): JsonResponse
    {
        $params = $request->validate([
            'id' => 'required',
            'status' => 'required'
        ]);
        try {
            $position = $this->positionService->update(
                $params['holding_id'],
                $request->get('expected_sell'));
        } catch (UpdateException|ModelNotFoundException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($position, Response::HTTP_OK);
    }

    function getById(int $id): JsonResponse
    {
        try {
            $holding = $this->positionService->getById($id);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($holding, Response::HTTP_OK);
    }

    function getByPortfolio(int $portfolioId): JsonResponse
    {
        try {
            $holdings = $this->positionService->getByPortfolio($portfolioId);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($holdings, Response::HTTP_OK);
    }

    function getByUser(): JsonResponse
    {
        try {
            $holdings = $this->positionService->getByUser(Auth::id());
        } catch (ModelNotFoundException $e) {
            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($holdings, Response::HTTP_OK);
    }
}
