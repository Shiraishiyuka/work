@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/correctionrequest.css') }}" />
<link rel="stylesheet" href="{{ asset('css/header.css') }}" />
@endsection

@section('header')
@include('partials.header')
@endsection


@section('content')
<div class="correction_screen">
    <form action="" method="POST">
        @csrf
        <input type="hidden" name="user_id" value=""> <!-- `user_id` を渡す -->
        <div class="title-space">
            <div class="title-line"></div>
            <span class="title-text">勤怠詳細</span>
        </div>
    </div>

    <table class="table">
        <tr>
            <th>名前</th>
            <th>日付</th>
            <th>出勤・退勤</th>
            <th>休憩</th>
            <th>休憩２</th>
            <th>備考</th>
        </tr>

        <tr>
            <td>テスト太郎</td>
            <td>２月７日</td>
            <td><input type="time" name=""></td>
            <td><input type="time" name="end_time" value=""></td>
            <td><input type="number" name="break_minutes" value=""></td>
            <td><input type="text" name="remarks" value=""></td>
        </tr>
    </table>

    <div class="button">
        <button type="submit" class="button-submit">承認</button>
    </div>
    </form>
</div>

@endsection
