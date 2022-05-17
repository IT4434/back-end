<?php

namespace App\Repositories;

use App\Models\Image;

class ImageRepository extends BaseRepository
{

    public function setModel(): string
    {
        return Image::class;
    }

    public function storeImages(int $id,array $paths, string $type)
    {
        foreach ($paths as $path) {
            $this->model->create([
                'image_path' => $path,
                'imageable_id' => $id,
                'imageable_type' => config('const' . $type),
            ]);
        }
    }
}
