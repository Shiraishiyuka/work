@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance_list.css') }}" />
<link rel="stylesheet" href="{{ asset('css/header.css') }}" />
@endsection

@section('header')
@include('partials.admin_header')
@endsection


@section('content')
<div class="attendance-list_screen">
    <div class="attendance-list_screen_inner">
        <div class="title-space">
            <div class="title-line"></div>
            <span class="title-text">スタッフ一覧</span>
        </div>
    

    <div class="attendance">
        <table class="attendance-record">
            <tr>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>月次勤怠</th>
            </tr>
            @foreach ($users as $user)
<tr>
    <td class="with-top-border">{{ $user->name }}</td>
    <td class="with-top-border">{{ $user->email }}</td>
    <td class="with-top-border"><a href="{{ route('by_staff', ['id' => $user->id]) }}">詳細</a></td>
</tr>
@endforeach
        </table>
    </div>
    </div>
</div>
@endsection