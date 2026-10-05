<?php

namespace App\Http\Middleware;

use App\Helpers\CustomImage;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Aturan upload gambar seragam di seluruh situs (admin & publik):
 * hanya JPG/JPEG/PNG/WEBP, maksimal 2 MB. Berlaku untuk setiap file
 * yang terdeteksi sebagai gambar; dokumen (PDF, DOCX, dll.) tidak disentuh.
 */
class ValidateImageUploads
{
    /** Ekstensi yang dianggap "gambar" walau formatnya tidak diizinkan. */
    private const IMAGE_LIKE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'jfif', 'heic', 'heif', 'svg', 'tif', 'tiff', 'avif', 'ico'];

    public function handle(Request $request, Closure $next): Response
    {
        $errors = [];
        foreach ($this->flatten($request->allFiles()) as $field => $file) {
            if ($message = self::check($file)) {
                $errors[$field] = $message;
            }
        }

        if ($errors) {
            // Upload gambar TinyMCE memakai XHR & membaca JSON, bukan redirect.
            if ($request->routeIs('tinymce.upload_image', 'news.upload_image')) {
                return response()->json(['error' => ['message' => reset($errors)]], 422);
            }

            // Juga sebagai flash 'error' agar tampil di halaman yang tidak menampilkan error per field.
            if ($request->hasSession()) {
                $request->session()->flash('error', reset($errors));
            }

            throw ValidationException::withMessages($errors);
        }

        return $next($request);
    }

    /** Pesan error bila file gambar melanggar aturan, null bila lolos / bukan gambar. */
    public static function check(UploadedFile $file): ?string
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $imageLike = in_array($ext, self::IMAGE_LIKE_EXTENSIONS, true);

        // Upload gagal (mis. melebihi batas server) belum punya file sementara untuk dicek MIME-nya.
        if (! $file->isValid()) {
            return $imageLike ? 'Gambar gagal diunggah. Ukuran maksimal '.CustomImage::MAX_IMAGE_KB / 1024 .' MB.' : null;
        }

        $mime = strtolower((string) $file->getMimeType());
        if (! $imageLike && ! str_starts_with($mime, 'image/')) {
            return null;
        }

        if (! in_array($ext, CustomImage::ALLOWED_IMAGE_EXTENSIONS, true)
            || ! in_array($mime, CustomImage::ALLOWED_IMAGE_MIMES, true)) {
            return 'Format gambar harus JPG, JPEG, PNG, atau WEBP.';
        }

        if ($file->getSize() > CustomImage::MAX_IMAGE_KB * 1024) {
            return 'Ukuran gambar maksimal '.CustomImage::MAX_IMAGE_KB / 1024 .' MB.';
        }

        return null;
    }

    /** @return array<string, UploadedFile> kunci dot-notation, mis. other_img.0 */
    private function flatten(array $files, string $prefix = ''): array
    {
        $out = [];
        foreach ($files as $key => $value) {
            $name = $prefix === '' ? (string) $key : $prefix.'.'.$key;
            if ($value instanceof UploadedFile) {
                $out[$name] = $value;
            } elseif (is_array($value)) {
                $out += $this->flatten($value, $name);
            }
        }

        return $out;
    }
}
