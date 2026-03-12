<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
class CustomImage 
{
	public static function storeFile($file, $path)
    {
  
        $img = 'img-' . time() . uniqid() . '.' . $file->getClientOriginalExtension();
        $imagePath = $file->storeAs($path, $img, 'public');

        // $output_file = 'public/certification/qr-code/qr-' . $payload['slug'] . '.png';
        Storage::disk('local')->put($img, $imagePath);

        // Storage::putFile($imagePath, $file);

        return [
            'name' => $img,
            'imagePath' => $imagePath,
        ];
    }
    
	public static function storeImage($file, $path)
    {
  
        $img = 'img-' . time() . uniqid() . '.' . $file->getClientOriginalExtension();
        $imagePath = "public/".$file->storeAs($path, $img, 'public');

        $image = Image::make(Storage::get($imagePath))->encode('jpg', 50);

        $image->resize(500, null, function ($constraint) {
		    $constraint->aspectRatio();
		});

        Storage::put($imagePath, (string) $image->encode());

        return [
            'name' => $img,
            'imagePath' => $imagePath,
        ];
    }

    public static function storeIcon($file, $path)
    {
        $img = 'img-' . time() . uniqid() . '.' . $file->getClientOriginalExtension();
        $imagePath = $file->storeAs($path, $img, 'public');

        $image = Image::make(Storage::get($imagePath))->encode('jpg', 50);
        $image->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        Storage::put($imagePath, (string) $image->encode());

        return [
            'name' => $img,
            'imagePath' => $imagePath,
        ];
    }

}