@extends('admin.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}" />
@endsection

@section('content')
<form class="login_form" action="{{ route('attendance.show') }}" method="post">
    @csrf
    <h1 class="login-title">管理者ログイン</h1>
    <div class="login-content">
        <!-- ユーザー名/メールアドレス -->
        <div class="form-group">
            <label>メールアドレス
                <input type="email" class="text" name="email" value="{{ old('email') }}" />
            </label>
            @error('email')
            <div class="form_error">{{ $message }}</div>
            @enderror
        </div>

        <!-- パスワード -->
        <div class="form-group">
            <label>パスワード
                <input type="password" class="text" name="password">
            </label>
            @error('password')
            <div class="form_error">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- ログインボタン -->
    <div class="button">
        <button class="button-submit">登録する</button>
    </div>
</form>


@endsection