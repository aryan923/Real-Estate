<?php

namespace App\Classes;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageuploadHelper
{
    public static function upload(UploadedFile $file, string $folder = 'settings'): string
    {
        $name = now()->format('YmdHis') . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();

        return $file->storeAs($folder, $name, 'public');
    }

    public static function getUploadedFileUrl(?string $path): string
    {
        if (!$path) {
            return '';
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}
