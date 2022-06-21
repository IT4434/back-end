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

    public function getFavoriteProduct($user)
    {
        $favoriteList = $user->favoriteProduct()->latest()->get();
        $favoriteList->load('images');

        return $favoriteList;
    }

    public function addFavoriteProduct($user, $productId)
    {
        return $user->favoriteProduct()->attach($productId);
    }

    public function removeFavoriteProduct($user, $productId)
    {
        return $user->favoriteProduct()->detach($productId);
    }
}
