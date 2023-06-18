<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class FileHelper
{
    public static function uploadFile($file, $folderName = null, $filePrefix = null): string
    {
        $defaultFileName = Carbon::now()->timestamp . '-' . $file->getClientOriginalName();
        $fileName = $filePrefix ? $filePrefix . '.' . $file->getClientOriginalExtension() : $defaultFileName;
        return $file->storeAs(self::getPath($folderName), $fileName, ['disk' => 'public']);
    }

    private static function getPath(string $folderName): string
    {
        return $folderName;
    }

    public static function getFileUrl(string $filePath): \Illuminate\Foundation\Application|string|\Illuminate\Contracts\Routing\UrlGenerator|\Illuminate\Contracts\Foundation\Application
    {
        return url(Storage::url($filePath));
    }

    public static function downloadFile(string $filePath):
    bool|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        if (file_exists($filePath)) {
            return response()->download($filePath);
        }

        return false;
    }

    public static function deleteFile($folderName, $fileName): bool
    {
        $filePath = self::getPath($folderName) . $fileName;

        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        return true;
    }
}
