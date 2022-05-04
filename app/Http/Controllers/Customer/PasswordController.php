<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\Password;

class PasswordController extends Controller
{
    /**
     * Send reset password link
     *
     * @param ForgetPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forget(ForgetPasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        $status = Password::sendResetLink($request->only('email'));
        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['success' => __($status)], config('response.HTTP_OK'));
        }

        return response()->json(['error' => __($status)], config('response.HTTP_BAD_REQUEST'));
    }

    /**
     * Reset user's password
     *
     * @param ResetPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(ResetPasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        $status = Password::reset($request->all(), function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
        });

        if ($status === Password::INVALID_TOKEN) {
            return response()->json(['error' => __($status)], config('response.HTTP_BAD_REQUEST'));
        }

        return response()->json(['success' => __('Password successfully reset')], config('response.HTTP_OK'));
    }

    /**
     * @param ChangePasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function change(ChangePasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['success' => __('Password successfully changed')], config('response.HTTP_OK'));
    }
}
