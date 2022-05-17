<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ImageService
{
    public function __construct()
    {

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

        $image = Image::make($file)->orientate();
        $size_array = [
            's' => [20, 20],
            'l' => [50, 50],
        ];

        $image_size_paths = [];

        foreach ($size_array as $key => $value) {
            $image_id = uniqid();
            $local_path = $tmp_dir . '/' . $image_id;
            $image_size_paths[$key] = [
                'image_id' => $image_id,
                'local_path' => $local_path,
            ];

            if ($image->height() > $image->width()) {
                Image::make($file)->orientate()->widen($value[0], function ($constraint) {
                    $constraint->aspectRatio();
                })->fit($value[0], $value[1], null, 'center')->save($local_path);
            } else {
                Image::make($file)->orientate()->heighten($value[0], function ($constraint) {
                    $constraint->aspectRatio();
                })->fit($value[0], $value[1], null, 'center')->save($local_path);
            }
        }

        return $image_size_paths;
    }

    /**
     * Upload images to S3 storage
     *
     * @param array $image_size_paths
     * @return array
     */
    public function s3UploadImages(array $image_size_paths): array
    {
        $paths = [];
        foreach ($image_size_paths as $key => $value) {
            $paths[] = Storage::disk('s3')->putFileAs('images/', $value['local_path'], $value['image_id']);
        }

        return $paths;
    }

    public function store()
    {

    }
}
