<?php

namespace App\Services;

use App\Repositories\ImageRepository;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ImageService
{
    protected $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    /**
     * @param UploadedFile $file
     * @return array
     */
    public function resizeImage(UploadedFile $file): array
    {
        $tmp_dir = public_path() . '/storage';
        if (!file_exists($tmp_dir)) {
           File::makeDirectory($tmp_dir, 0775, true);
        }

        $image_id = uniqid();
        $local_path = $tmp_dir . '/' . $image_id;
        $image = Image::make($file)->orientate();
        $image_path = [
          'image_id' => $image_id,
          'local_path' => $local_path,
        ];

        $size = [1500, 1000];

        if ($image->height() > $image->width()) {
            Image::make($file)->orientate()->widen($size[0], function ($constraint) {
                $constraint->aspectRatio();
            })->fit($size[0], $size[1], null, 'center')->save($local_path);
        } else {
            Image::make($file)->orientate()->heighten($size[0], function ($constraint) {
                $constraint->aspectRatio();
            })->fit($size[0], $size[1], null, 'center')->save($local_path);
        }

        return $image_path;
    }

    /**
     * Upload images to S3 storage
     *
     * @param array $path
     * @param string $type
     * @return string
     */
    public function s3UploadImages(array $path, string $type): string
    {
//        $paths = [];
//        foreach ($image_size_paths as $key => $value) {
//            $paths[] = Storage::disk('s3')->putFileAs('images/', $value['local_path'], $value['image_id']);
//        }

        return Storage::disk('s3')->putFileAs('images/' . $type, $path['local_path'], $path['image_id']);
    }

    public function storeImagePaths(string $path, int $imageable_id, string $imageable_type)
    {

        return $this->imageRepository->storeImages($imageable_id, $path, $imageable_type);
    }

    public function updateImagePath(string $path, int $imageable_id, string $imageable_type)
    {
        return $this->imageRepository->updateImagePath($path, $imageable_id, $imageable_type);
    }

    public function s3DeleteImages($images)
    {
        foreach ($images as $image) {
            Storage::disk('s3')->delete($image->image_path);
        }
    }
}
