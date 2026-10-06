<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
class CustomImage 
{
    // Aturan gambar seragam di seluruh situs (lihat juga middleware ValidateImageUploads).
    const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    const ALLOWED_IMAGE_MIMES = ['image/jpeg', 'image/png', 'image/webp'];

    const MAX_IMAGE_KB = 5120;

    const ALLOWED_FILE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

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

        if (in_array($ext, self::ALLOWED_IMAGE_EXTENSIONS, true) && $file->getSize() > self::MAX_IMAGE_KB * 1024) {
            throw new \InvalidArgumentException('Ukuran gambar maksimal ' . (self::MAX_IMAGE_KB / 1024) . ' MB.');
        }

        return $ext;
    }

	public static function storeFile($file, $path)
    {
        self::validateUpload($file, self::ALLOWED_FILE_EXTENSIONS);

        $img = 'img-' . time() . uniqid() . '.' . strtolower($file->getClientOriginalExtension());
        $imagePath = $file->storeAs($path, $img, 'public');

        return [
            'name' => $img,
            'imagePath' => $imagePath,
        ];
    }
    
	public static function storeImage($file, $path)
    {
        self::validateUpload($file, self::ALLOWED_IMAGE_EXTENSIONS);

        $img = 'img-' . time() . uniqid() . '.jpg';
        $imagePath = $file->storeAs($path, $img, 'public');

        $image = Image::make(Storage::disk('public')->get($imagePath))->encode('jpg', 50);

        $image->resize(500, null, function ($constraint) {
		    $constraint->aspectRatio();
		});

        Storage::disk('public')->put($imagePath, (string) $image->encode());

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

        $image = Image::make(Storage::disk('public')->get($imagePath))->encode('jpg', 50);
        $image->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        Storage::disk('public')->put($imagePath, (string) $image->encode());

        return [
            'name' => $img,
            'imagePath' => $imagePath,
        ];
    }

}