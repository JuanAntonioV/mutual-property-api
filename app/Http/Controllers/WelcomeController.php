<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class WelcomeController extends Controller
{

    public function appDetails(): JsonResponse
    {
        $data = [
            'name' => config('app.name'),
            'version' => config('app.version'),
            'env' => config('app.env'),
            'versioning' => [
                'api' => 'v1',
                'php' => '^8.2.5',
                'laravel' => '^10.10',
                'node' => '^18.16.0',
                'npm' => '^9.6.4',
            ]
        ];

        $res = ResponseHelper::success($data, "Berhasil mendapatkan data aplikasi");

        return response()->json($res, $res['code']);
    }

    public function welcome(): View
    {
        return \view('welcome');
    }
}
