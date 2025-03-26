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

     protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(); // CSRFチェックを無効化
    }

    /**
     * メールアドレスが未入力のとき、バリデーションエラーが発生することを確認
     */
    public function test_user_registration_fails_without_email()
    {
        $response = $this->post('/register', [
            'name' => 'User',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        // メールアドレスが未入力のため、バリデーションエラーが発生することを確認
        $response->assertSessionHasErrors(['email']);
    }

    /**
     * 名前が未入力のとき、バリデーションエラーが発生することを確認
     */
    public function test_user_registration_fails_without_name()
    {
        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        // 名前が未入力のため、バリデーションエラーが発生することを確認
        $response->assertSessionHasErrors(['name']);
    }

    /**
     * 正常なデータで会員登録が成功することを確認
     */
    public function test_user_registration_succeeds_with_valid_data()
{
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ]);

    // 登録したユーザーを取得
    $user = \App\Models\User::where('email', 'test@example.com')->first();

    // ユーザーがデータベースに登録されていることを確認
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com'
    ]);

    // ログイン状態にする
    $this->actingAs($user);

    // 認証後に `/home` にリダイレクトすることを確認
    $response->assertRedirect('/attendance');
}
}
