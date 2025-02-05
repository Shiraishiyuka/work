@extends('admin.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance_list.css') }}" />
<link rel="stylesheet" href="{{ asset('css/header.css') }}" />
@endsection

@section('header')
@include('partials.header')
@endsection


@section('content')
<div class="attendance-list_screen">
    <div class="attendance-list_screen_inner">
        <div class="title-space">
            <div class="title-line"></div>
            <span class="title-text">スタッフ一覧</span>
        </div>
    </div>

    <div class="attendance">
        <table class="attendance-record">
            <tr>名前</tr>
            <tr>メールアドレス</tr>
            <tr>月次勤怠</tr>
            @foreach ($users as $user)

            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>詳細</td>
            @endforeach
        </table>
    </div>
</div>
@endsection