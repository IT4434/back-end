<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends BaseRepository
{
    public function setModel(): string
    {
        // TODO: Implement setModel() method.
        return Category::class;
    }

    /**
     * Get all categories ordered by place
     *
     * @return mixed
     */
    public function index()
    {
        return $this->model->orderBy('place')->get();
    }
}
