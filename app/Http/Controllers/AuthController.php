<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(private UserService $service) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $this->service->checkLogin($request->validated());

        if (! $data['success']) {
            return response()->json([
                'message' => $data['message'],
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'message' => $data['message'],
            'token' => $data['user']->createToken($data['user']->email)->plainTextToken,
            'user' => (new UserResource($data['user'])),
        ], Response::HTTP_OK);
    }
}
