<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;

class CloudinaryUploader
{
    /**
     * Upload a file to Cloudinary and return its full secure URL.
     */
    public static function store(UploadedFile $file, string $folder): string
    {
        $cloudinary = new Cloudinary(config('cloudinary.cloud_url'));

        $result = $cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder' => $folder,
        ]);

        return $result['secure_url'];
    }

    /**
     * Delete a previously uploaded file given its full Cloudinary URL.
     */
    public static function delete(string $url): void
    {
        $publicId = self::publicIdFromUrl($url);

        if (!$publicId) {
            return;
        }

        $cloudinary = new Cloudinary(config('cloudinary.cloud_url'));
        $cloudinary->uploadApi()->destroy($publicId);
    }

    private static function publicIdFromUrl(string $url): ?string
    {
        // e.g. https://res.cloudinary.com/<cloud>/image/upload/v169.../galleries/abc123.jpg
        // public_id we need is "galleries/abc123"
        if (!preg_match('#/upload/(?:v\d+/)?(.+)\.\w+$#', $url, $matches)) {
            return null;
        }

        return $matches[1];
    }
}
