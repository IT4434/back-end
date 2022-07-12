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

    public function updateImagePath(string $path, int $imageable_id, string $imageable_type) {
        $image = $this->model->where('imageable_id', $imageable_id)
            ->where('imageable_type', $imageable_type)
            ->first();
        if ($image) {
            $image->image_path = $path;
            $image->save();
        }

        return $image;
    }
}
