@extends('layouts.app')

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
            <span class="title-text">勤怠一覧</span>
        </div>

        <div class="indicate">
            <div class="previous-month">
                <div class="previous-month_image"><img src="{{ asset('storage/images/images.png') }}" class="images" alt="images"></div>
                <div class="previous-month_text">前月</div>
            </div>

            <div class="calendar">
                <div class="calendar_image"><img src="{{ asset('storage/images/download-1.png') }}" class="download" alt="download"></div>
                <div class="calendar_text">2023/06</div>
            </div>

            <div class="later-month">
                <div class="later-month_image"><img src="{{ asset('storage/images/images.png') }}" class="images" alt="images"></div>
                <div class="later-month_text">後月</div>
            </div>
        </div>

        <div class="attendance">
            <table class="attendance-record">
                <tr>
                    <th>日付</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>
                <tr>
                    <td>日付</td>
                    <td>出勤時間</td>
                    <td>退勤時間</td>
                    <td>休憩合計時間</td>
                    <td>退勤合計時間</td>
                    <td>詳細</td>
                </tr>
            </table>
        </div>
        
    </div>
</div>
@endsection