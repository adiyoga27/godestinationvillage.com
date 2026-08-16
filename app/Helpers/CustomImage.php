<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
class CustomImage 
{
    const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'jfif', 'heic', 'heif'];

    const ALLOWED_FILE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'jfif', 'heic', 'heif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

    const DANGEROUS_MIME_PATTERN = '/(php|x-httpd-php|html|x-httpd-php-source|application\/x-sh|text\/x-script|application\/x-cgi)/i';

    private static function validateUpload($file, array $allowedExtensions)
    {
        if (!$file || !$file->isValid()) {
            throw new \InvalidArgumentException('File upload tidak valid.');
        }

        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, $allowedExtensions, true)) {
            throw new \InvalidArgumentException('Ekstensi file tidak diizinkan: ' . $ext);
        }

        $mime = (string) $file->getMimeType();
        if (preg_match(self::DANGEROUS_MIME_PATTERN, $mime)) {
            throw new \InvalidArgumentException('Tipe file tidak diizinkan.');
        }

        return $ext;
    }

	public static function storeFile($file, $path)
    {
        self::validateUpload($file, self::ALLOWED_FILE_EXTENSIONS);

        $img = 'img-' . time() . uniqid() . '.' . strtolower($file->getClientOriginalExtension());
        $imagePath = $file->storeAs($path, $img, 'public');

        Storage::disk('local')->put($img, $imagePath);

        return [
            'name' => $img,
            'imagePath' => $imagePath,
        ];
    }
    
	public static function storeImage($file, $path)
    {
        self::validateUpload($file, self::ALLOWED_IMAGE_EXTENSIONS);

        $img = 'img-' . time() . uniqid() . '.jpg';
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
        self::validateUpload($file, self::ALLOWED_IMAGE_EXTENSIONS);

        $img = 'img-' . time() . uniqid() . '.jpg';
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