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

        return $this->userRepository->store($data)->sendEmailVerificationNotification();
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
}
