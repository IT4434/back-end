<?php

namespace App\Repositories;

abstract class BaseRepository
{
    protected $model;

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct()
    {
        $this->getModel();
    }

    abstract public function setModel();

    /**
     * Get model of children repository
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function getModel()
    {
        $this->model = app()->make($this->setModel());
    }

    /**
     * Get all models
     */
    public function index()
    {
        return $this->model->all();
    }

    /**
     * Get a specify model
     */
    public function show($id)
    {
        return $this->model->find($id);
    }

    /**
     * Create a new model
     */
    public function store($data)
    {
        return $this->model->create($data);
    }

    /**
     * Update a specified model
     */
    public function update($id, $data): bool
    {
        $result = $this->show($id);
        if ($result) {
            $result->update($data);

            return $result;
        }

        return false;
    }

    /**
     * Delete a specified model
     */
    public function delete($id): bool
    {
        $result = $this->show($id);
        if ($result) {
            return $result->delete();
        }

        return false;
    }
}
