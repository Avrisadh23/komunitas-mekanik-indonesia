<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    /**
     * Store an uploaded file on whichever disk is configured for this
     * environment and return the value to save on the model.
     */
    public static function store(UploadedFile $file, string $folder): string
    {
        if (config('filesystems.uploads') === 'cloudinary') {
            return CloudinaryUploader::store($file, $folder);
        }

        return $file->store($folder, 'public');
    }

    /**
     * Delete a previously stored file, given the value that was saved on
     * the model (either a Cloudinary URL or a legacy local disk path).
     */
    public static function delete(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            CloudinaryUploader::delete($path);
            return;
        }

        Storage::disk('public')->delete(str_replace('storage/', '', $path));
    }
}
