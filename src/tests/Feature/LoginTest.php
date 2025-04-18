<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        /*$this->withoutMiddleware();*/ // セッション必要なのでミドルウェアとおす
    }

    /** メールアドレス未入力のテスト */
    public function test_login_fails_without_email()
    {
        $response = $this->post('/login', [
            'password' => 'password123'
        ]);

        $errors = session('errors');
        $this->assertEquals('メールアドレスを入力してください。', $errors->first('email'));
    }

    /** パスワード未入力のテスト */
    public function test_login_fails_without_password()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com'
        ]);

        $errors = session('errors');
        $this->assertEquals('パスワードを入力してください', $errors->first('password'));
    }

    /** 登録されていないユーザーでログインしようとしたときのテスト */
    public function test_login_fails_with_invalid_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'unregistered@example.com',
            'password' => 'invalidpass'
        ]);

        $errors = session('errors');
        $this->assertEquals('ログイン情報が登録されていません', $errors->first('email'));
    }

    /** 正しいユーザー情報でログイン成功するかのテスト */
    public function test_login_succeeds_with_valid_credentials()
    {
        // すでに同じメールアドレスのユーザーが存在しないように削除
        User::where('email', 'test@example.com')->delete();
        
        User::create([
            'name' => 'テスト太郎_' . uniqid(),
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/two-factor-auth'); // 実際のログイン後リダイレクト先に合わせて変更
        $this->assertAuthenticated();
    }

    /* 管理者ログイン：失敗系テスト（メールなし）*/
    public function test_admin_login_fails_without_email()
    {
        $response = $this->post('/admin/login', [
            'password' => 'password123'
        ]);

        $errors = session('errors');
        $this->assertEquals('メールアドレスを入力してください。', $errors->first('email'));
    }

    /** 管理者ログイン：失敗系テスト（パスワードなし） */
    public function test_admin_login_fails_without_password()
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com'
        ]);

        $errors = session('errors');
        $this->assertEquals('パスワードを入力してください', $errors->first('password'));
    }

    /** 管理者ログイン：認証失敗時のメッセージ */
    public function test_admin_login_fails_with_wrong_credentials()
    {
        $response = $this->post('/admin/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpass'
        ]);

        $errors = session('errors');
        $this->assertEquals('ログイン情報が登録されていません', $errors->first('email'));
    }

    /** 管理者ログイン：成功する場合 */
    public function test_admin_login_succeeds_with_valid_credentials()
    {

        User::where('email', 'test@example.com')->delete();

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'is_admin' => true,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect(route('admin.attendance.list'));
        $this->assertAuthenticated();
    }
}
