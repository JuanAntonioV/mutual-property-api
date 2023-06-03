<?php

namespace Tests\Feature;

use App\Helpers\ResponseHelper;
use Tests\TestCase;

class WelcomeTest extends TestCase
{
    public function test_the_v1_route_is_returns_a_app_details(): void
    {
        $res = $this->get('/v1');

        $res->assertStatus(200);

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

        $expectedRes = ResponseHelper::success($data, "Berhasil mendapatkan data aplikasi");

        $res->assertSimilarJson($expectedRes);
    }

    public function test_the_web_route_is_returns_a_default_laravel_view(): void
    {
        $res = $this->get('/web');

        $res->assertSeeText(['Laravel v10.13.1 (PHP v8.2.5)', 'Documentation']);
    }
}
