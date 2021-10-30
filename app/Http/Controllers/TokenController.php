<?php

namespace App\Http\Controllers;

use App\Exceptions\DeleteException;
use App\Exceptions\SaveException;
use App\Exceptions\UpdateException;
use App\Http\Services\TokenService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenController extends Controller
{
    private TokenService $tokenService;

    function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    function create(Request $request): JsonResponse
    {
        $params = $request->validate([
            'name' => 'required'
        ]);
        try {
            $token = $this->tokenService->create($params['name']);
        } catch (SaveException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($token);
    }

    function update(Request $request): JsonResponse
    {
        $params = $request->validate([
            'name' => 'required'
        ]);
        try {
            $token = $this->tokenService->update($params['name']);
        } catch (UpdateException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($token);
    }

    function delete($id): JsonResponse
    {
        try {
            $this->tokenService->delete($id);
        } catch (DeleteException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    function getByName($name): JsonResponse
    {
        try {
            $token = $this->tokenService->getByName($name);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($token);
    }

    function getById($id): JsonResponse
    {
        try {
            $token = $this->tokenService->getById($id);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse($token);
    }
}
