<?php

namespace App\Http\Controllers;

use App\Http\Services\LoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    private LoginService $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function generateApiToken(Request $request): JsonResponse
    {
        $params = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required'
        ]);

        try {
            $token = $this->loginService->generateApiToken(
                $params['email'],
                $params['password'],
                $params['device_name']
            );
        } catch (ValidationException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse(['token' => $token]);
    }
}
