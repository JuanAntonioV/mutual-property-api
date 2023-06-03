<?php

namespace Tests\Feature;

use App\Helpers\ResponseHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_validators(): void
    {
        $payload = [
            'full_name' => '',
            'phone_number' => '',
            'email' => '',
            'password' => '',
        ];

        $res = $this->post('v1/auth/register', $payload);

        $res->assertStatus(422);
    }

    public function test_login_validators(): void
    {
        $payload = [
            'email' => '',
            'password' => '',
        ];

        $res = $this->post('v1/auth/login', $payload);

        $res->assertStatus(422);
    }

    public function test_unauthorized_login(): void
    {
        $this->test_register_new_user();

        $payload = [
            'email' => 'user@email.com',
            'password' => 'user123',
        ];

        $res = $this->post('v1/auth/login', $payload);

        $res->assertStatus(200);
        $res->assertSeeText(['user', 'token']);
    }

    public function test_register_new_user(): void
    {
        $payload = [
            'full_name' => 'User Testing',
            'phone_number' => '081238123122',
            'email' => 'user@email.com',
            'password' => 'user123',
        ];

        $res = $this->post('v1/auth/register', $payload);

        $res->assertStatus(200);
        $res->assertSimilarJson(ResponseHelper::success(null, 'Berhasil mendaftarkan akun'));
    }
}
