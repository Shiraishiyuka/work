@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mail.css') }}" />
@endsection

@section('content')

<div class="container">
    <p>認証されました。</p>

<!-- URLを表示せずにボタンを使う -->
    <div class="certification-button">
        <form action="{{ route('two-factor.verify') }}" method="get">
    @csrf
            <input type="hidden" name="token" value="{{ session('two_factor_token') }}">
            <button type="submit" class="certification">出勤登録画面へ</button>

        </form>
    </div>
</div>

@endsection