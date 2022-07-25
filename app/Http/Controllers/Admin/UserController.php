<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $users = $this->userService->getUserList();

        return UserResource::collection($users);
    }

    /**
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function blockUser(User $user, Request $request): \Illuminate\Http\JsonResponse
    {
        $data = [
            'is_blocked' => $request->is_blocked,
        ];
        $result = $this->userService->blockUser($user->id, $data);

        if ($result) {
            return response()->json(['success' => 'Successfully'], config('response.HTTP_OK'));
        }

        return response()->json(['error' => 'Failed'], config('response.HTTP_BAD_REQUEST'));
    }
}
