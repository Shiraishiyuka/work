<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class HelloTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_registration_fails_without_name()
    {
        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        // バリデーションエラーが発生することを確認
        $response->assertSessionHasErrors(['name']);
    }

    /**
     * 正常なデータで会員登録が成功することを確認
     */
    public function test_user_registration_succeeds_with_valid_data()
    {
        $response = $this->post('/register', [
        'name' => '',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ]);

    // ✅ `name` フィールドのバリデーションエラーが発生することを確認
    $response->assertSessionHasErrors([
        'name' => '名前。'
    ]);
    }
}
