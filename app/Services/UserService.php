<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    protected $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register new user
     *
     * @param $data
     * @return mixed
     */
    public function register($data)
    {
        $password = bcrypt($data['password']);
        $data = array_merge($data, ['password' => $password]);
        $user = $this->userRepository->store($data);
        $user->sendEmailVerificationNotification();

        return $user;
    }

    /**
     * Find user by user's id
     *
     * @param $id
     * @return mixed
     */
    public function findUserById($id)
    {
        return $this->userRepository->show($id);
    }

    /**
     * Find user by user's email
     *
     * @param $email
     * @return mixed
     */
    public function findUserByEmail($email)
    {
        return $this->userRepository->findUserByEmail($email);
    }

    public function getFavoriteProduct($user): \Illuminate\Database\Eloquent\Collection
    {
        return $this->userRepository->getFavoriteProduct($user);
    }

    public function addFavoriteProduct($user, $productId)
    {
        return $this->userRepository->addFavoriteProduct($user, $productId);
    }

    public function removeFavoriteProduct($user, $productId)
    {
        return $this->userRepository->removeFavoriteProduct($user, $productId);
    }

    public function getUserList()
    {
        return $this->userRepository->index();
    }

    public function blockUser($user_id, $data)
    {
        return $this->userRepository->update($user_id, $data);
    }
}
