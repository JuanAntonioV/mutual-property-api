<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class FileHelper
{
    public static function uploadFile($file, $folderName = null, $filePrefix = null): string
    {
        if (!$filePrefix) {
            $defaultFileName = Carbon::now()->timestamp . '-' . $file->getClientOriginalName();
            $fileName = $defaultFileName;
        } else {
            $fileName = $filePrefix . '.' . $file->getClientOriginalExtension();
        }

        return $file->storeAs(self::getPath($folderName), $fileName, ['disk' => 'public']);
    }

    private static function getPath(string $folderName): string
    {
        return $folderName;
    }

    public static function getFileUrl(string|null $filePath)
    {
        return url(Storage::url($filePath));
    }

    public static function downloadFile(string $filePath): bool|\Symfony\Component\HttpFoundation\StreamedResponse
    {
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->download($filePath);
        }

        return false;
    }

    public static function deleteFile($filePath): bool
    {
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        return true;
    }
}
