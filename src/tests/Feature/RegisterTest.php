<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(); // CSRF無効化
    }

    /** 名前未入力のメッセージ確認 */
    public function test_name_required_message()
    {
        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $errors = session('errors');
        $this->assertEquals('名前を入力してください。', $errors->first('name'));
    }

    /** メールアドレス未入力のメッセージ確認 */
    public function test_email_required_message()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $errors = session('errors');
        $this->assertEquals('メールアドレスを入力してください。', $errors->first('email'));
    }

    /** メール形式エラーのメッセージ確認 */
    public function test_email_format_message()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $errors = session('errors');
        $this->assertEquals('メールアドレスは「ユーザー名@ドメイン」の形式で入力してください。', $errors->first('email'));
    }

    /** パスワード未入力メッセージ */
    public function test_password_required_message()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password_confirmation' => 'password123',
        ]);

        $errors = session('errors');
        $this->assertEquals('パスワードを入力してください。', $errors->first('password'));
    }

    /** パスワード文字数不足 */
    public function test_password_min_message()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        $errors = session('errors');
        $this->assertEquals('パスワードは8文字以上で入力してください。', $errors->first('password'));
    }

    /** パスワード確認不一致 */
    public function test_password_confirmation_message()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ]);

        $errors = session('errors');
        $this->assertEquals('パスワードと一致しません。', $errors->first('password'));
    }

    /** メールアドレスの重複チェックメッセージ */
    public function test_email_unique_message()
    {

        User::create([
            'name' => '既存ユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $errors = session('errors');
        $this->assertEquals('このメールアドレスは既に登録されています。', $errors->first('email'));
    }

    public function test_valid_user_is_saved_to_database()
{
    $response = $this->post('/register', [
        'name' => '新しいユーザー',
        'email' => 'saved_user@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);


    $this->assertDatabaseHas('users', [
        'email' => 'saved_user@example.com',
        'name' => '新しいユーザー',
    ]);


    $response->assertRedirect('/login');
}
}
