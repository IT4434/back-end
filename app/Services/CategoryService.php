<?php

namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->categoryRepository->index();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        return $this->categoryRepository->store($data);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        return $this->categoryRepository->update($id, $data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        return $this->categoryRepository->delete($id);
    }
}
