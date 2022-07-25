<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class VerificationController extends Controller
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
     * Verify user's email
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify($id, Request $request): \Illuminate\Http\RedirectResponse
    {
        if (!$request->hasValidSignature()) {
            $error = urlencode(__('Invalid/Expired url provided'));
            return redirect()->to('http://localhost:3000/login?error='.$error);
//            return response()->json(['error' => __('Invalid/Expired url provided')], config('response.HTTP_BAD_REQUEST'));
        }

        $user = $this->userService->findUserById($id);
        if ($user) {
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }
        } else {
            $error = urlencode(__('User email not found'));
            return redirect()->to('http://localhost:3000/login?error='.$error);
//            return response()->json(['error' => __('User email not found')], config('response.HTTP_BAD_REQUEST'));
        }

        return redirect()->to('http://localhost:3000/login');
    }

    /**
     * Resend verification email to user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->userService->findUserByEmail($request->email);
        if (!$user) {
            return response()->json(['error' => __('Email has not been used for any user')], config('response.HTTP_BAD_REQUEST'));
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['success' => __('Email has been verified')], config('response.HTTP_OK'));
        } else {
            $user->sendEmailVerificationNotification();

            return response()->json(['success' => __('Email verification link sent to your email')], config('response.HTTP_OK'));
        }
    }
}
