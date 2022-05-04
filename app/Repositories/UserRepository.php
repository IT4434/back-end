<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    public function setModel(): string
    {
        // TODO: Implement setModel() method.
        return User::class;
    }

    /**
     * @param $email
     * @return mixed
     */
    public function findUserByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }
}
