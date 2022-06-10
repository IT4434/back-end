<?php

namespace App\Repositories;

use App\Models\Image;

class ImageRepository extends BaseRepository
{

    public function setModel(): string
    {
        return Image::class;
    }

    public function storeImages(int $id, string $path, string $type)
    {
        return $this->model->create([
            'image_path' => $path,
            'imageable_id' => $id,
            'imageable_type' => $type,
        ]);
    }
}
