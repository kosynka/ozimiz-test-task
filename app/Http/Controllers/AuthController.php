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

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     operationId="login",
     *     tags={"auth"},
     *     summary="login to get token",
     *     description="login to get token",
     * 
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data for storing collection",
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", default="fake@mail.com"),
     *             @OA\Property(property="password", type="string", default="password"),
     *             @OA\Property(property="password_confirmation", type="string", default="password"),
     *         ),
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="HTTP_OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 default="success"
     *             ),
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 default="1|oBbCtlOy0bbZogdrBucE4oghfyv5v5mWkDWTIoB5d33aeca4"
     *             ),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="number", default="1"),
     *                 @OA\Property(property="name", type="string", default="Test User"),
     *                 @OA\Property(property="email", type="string", default="fake@mail.com"),
     *                 @OA\Property(property="created_at", type="date", default="2024-04-08T23:51:10.000000Z"),
     *                 @OA\Property(property="updated_at", type="date", default="2024-04-08T23:51:10.000000Z"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="HTTP_BAD_REQUEST",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="HTTP_UNAUTHORIZED",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="HTTP_NOT_FOUND",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="HTTP_INTERNAL_SERVER_ERROR",
     *     ),
     * )
     */
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
