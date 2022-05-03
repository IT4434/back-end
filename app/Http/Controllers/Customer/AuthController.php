<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\UserService;

class AuthController extends Controller
{
    protected $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Register new user
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->userService->register($request->all());
        if ($user) {
            return response()->json(['success' => __('Email verification link sent to your email')], config('response.HTTP_CREATED'));
        } else {
            return response()->json(['error' => __('Failed to create new account')], config('response.HTTP_BAD_REQUEST'));
        }
    }

    /**
     * Login user
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->userService->findUserByEmail($request->email);
        if ($user) {
            if (!$user->hasVerifiedEmail()) {
                return response()->json(['error' => __('Email has not been verified')], config('response.HTTP_BAD_REQUEST'));
            }
        } else {
            return response()->json(['error' => __('Email has not been registered')], config('response.HTTP_BAD_REQUEST'));
        }

        $token = auth()->attempt($request->all());
        if (! $token) {
            return response()->json(['error' => __('Incorrect email or password')], config('response.HTTP_UNAUTHORIZED'));
        }

        return $this->respondWithToken($token);
    }

    /**
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ], config('response.HTTP_OK'));
    }
}
