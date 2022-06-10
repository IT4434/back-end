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
        Image::make($file)->orientate()->save($local_path);
        $image_path = [
          'image_id' => $image_id,
          'local_path' => $tmp_dir . '/' . $image_id,
        ];
//        $size_array = [
//            's' => [1000, 1000],
//            'l' => [2000, 2000],
//        ];
//
//        $image_size_paths = [];
//
//        foreach ($size_array as $key => $value) {
//            $image_id = uniqid();
//            $local_path = $tmp_dir . '/' . $image_id;
//            $image_size_paths[$key] = [
//                'image_id' => $image_id,
//                'local_path' => $local_path,
//            ];
//
//            if ($image->height() > $image->width()) {
//                Image::make($file)->orientate()->widen($value[0], function ($constraint) {
//                    $constraint->aspectRatio();
//                })->fit($value[0], $value[1], null, 'center')->save($local_path);
//            } else {
//                Image::make($file)->orientate()->heighten($value[0], function ($constraint) {
//                    $constraint->aspectRatio();
//                })->fit($value[0], $value[1], null, 'center')->save($local_path);
//            }
//        }

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

    public function storeImagePaths(string $path, int $imageable_id, string $imageable_type){

        return $this->imageRepository->storeImages($imageable_id, $path, $imageable_type);
    }
}
