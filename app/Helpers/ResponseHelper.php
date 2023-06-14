<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ResponseHelper
{
    public static function success($data = null, $message = 'Berhasil mendapatkan data', $code =
    ResponseAlias::HTTP_OK):
    array
    {
        $res = [
            'code' => $code,
            'message' => $message,
        ];

        if ($data) {
            $res['results'] = $data;
        }

        return $res;
    }

    public static function error($message = null, $error = null, $code =
    ResponseAlias::HTTP_BAD_REQUEST): array
    {
        $res = [
            'code' => $code,
            'message' => $message,
        ];

        if ($error) {
            $res['errors'] = $error;
        }

        return $res;
    }

    public static function notFound($message = null, $code = ResponseAlias::HTTP_NOT_FOUND): array
    {
        return [
            'code' => $code,
            'message' => $message,
            'results' => [],
        ];
    }

    public static function serverError($error = null, $message = 'Terjadi kesalahan pada server', $code =
    ResponseAlias::HTTP_INTERNAL_SERVER_ERROR): array
    {
        return [
            'code' => $code,
            'message' => $message,
            'error' => $error,
        ];
    }
}
