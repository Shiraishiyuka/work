@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mail.css') }}" />
@endsection

@section('content')
<div class="container">
    <p>登録していただいたメールアドレスに認証メールを添付しました。</p>
    <p>メール認証を完了してください。</p>

    <div class="certification-button">
        <form action="{{ route('mail') }}" method="get">
        @csrf
        <button class="certification" type="submit">認証はこちらから</button>
        </form>
    </div>

    <div class="resend-bottom">

        <form action="{{ route('two-factor.resend') }}" method="post">
        @csrf
            <button type="submit" class="resend">認証メールを再送する</button>
    </form>
    </div>
</div>



@endsection