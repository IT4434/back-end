<?php

namespace App\Repositories;

use App\Models\OrderDetail;

class OrderDetailRepository extends BaseRepository
{

    public function setModel(): string
    {
        // TODO: Implement setModel() method.
        return OrderDetail::class;
    }

    public function createNewOrderDetails($data)
    {
        return $this->model->insert($data);
    }

    public function setIsRated($orderDetailId)
    {
        return $this->update($orderDetailId, ['is_rated' => '1']);
    }
}
