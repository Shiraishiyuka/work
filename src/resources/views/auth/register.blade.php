@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}" />
@endsection

@section('content')
<form class="register_form" action="{{ route('register') }}" method="post">
    @csrf

    <h1 class="register-title">会員登録</h1>
    <div class="register-content">
    <label>ユーザー名</label>
    <input type="text" class="text" name="name">
    <div class="form_error">
        @error('name')
            {{ $message }}
        @enderror
    </div>

    <label>メールアドレス
        <input type="email"  class="text" name="email">
    </label>
    <div class="form_error">
         @error('email')
            {{ $message }}
        @enderror
    </div>

    <label>パスワード
        <input type="password"  class="text" name="password">
    </label> 
    <div class="form_error">
         @error('password')
            {{ $message }}
        @enderror
    </div>

    <label>確認用パスワード<input type="password" class="text" name="password_confirmation">
    </label>
    <div class="form_error">
        @error('password_confirmation')
            {{ $message }}
        @enderror
    </div>
</div>

<div class="button">
        <button class="button-submit">会員登録</button>
    </div>

</form>

<div class="return_button">
        <form action="{{ route('login') }}" method="get">
            <button class="login_button-submit" type="submit">ログインはこちら</button>
  </form>
</div>

@endsection


