<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\ImageService;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;

class AccountController extends Controller
{
    protected $userService;
    protected $imageService;

    public function __construct(UserService $userService, ImageService $imageService)
    {
        $this->userService = $userService;
        $this->imageService = $imageService;
    }

    /**
     * @param User $user
     * @return UserResource
     */
    public function getAccountInformation(User $user): UserResource
    {
        $user->load('images');

        return new UserResource($user);
    }

    /**
     * @param User $user
     * @param RegisterRequest $request
     * @return UserResource
     */
    public function updateAccountInformation(User $user, RegisterRequest $request): UserResource
    {
        $user = $this->userService->updateInfo($user->id, $request->except('image', 'is_blocked'));
        if ($request->file('image')) {
            // Delete old images
            $images = $user->images()->get();
            if ($images) {
                $this->imageService->s3DeleteImages($images);
            }

            // Update image
            $file = $request->file('image');
            $path = $this->imageService->resizeImage($file);
            $s3_path = $this->imageService->s3UploadImages($path, 'users');
            if (!$user->images->isEmpty()) {
                $image = $this->imageService->updateImagePath($s3_path, $user->id, 'App\Models\User');
            } else {
                $image = $this->imageService->storeImagePaths($s3_path, $user->id, 'App\Models\User');
            }
        }
        $user->load('images');

        return new UserResource($user);
    }
}
