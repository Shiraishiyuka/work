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
            <span class="title-text">{{ $currentDate->translatedFormat('Y年m月d日（D）') }}</span>
        </div>


        <div class="indicate">
        <!-- 前日ボタン -->
            <div class="previous-month">
                <a href="{{ route('admin.attendance.list', ['date' => $previousDate]) }}" class="previous-month_link">
                <div class="previous-month_image">
                <img src="{{ asset('storage/images/images.png') }}" class="images" alt="前日">
                </div>
            <div class="previous-month_text">前日</div>
                </a>
            </div>

            <!-- カレンダー表示 -->
            <div class="calendar">
                <div class="calendar_image">
                    <a href="#">
                        <img src="{{ asset('storage/images/download-1.png') }}" class="download" alt="カレンダー">
                    </a>
                </div>
                <div class="calendar_text">{{ $currentDate->format('Y/m/d') }}</div>
            </div>

            <!-- 翌日ボタン -->
            <div class="later-month">
                <a href="{{ route('admin.attendance.list', ['date' => $nextDate]) }}" class="later-month_link">
                    <div class="later-month_image">
                        <img src="{{ asset('storage/images/images.png') }}" class="images" alt="翌日">
                    </div>
                    <div class="later-month_text">翌日</div>
                </a>
            </div>
        </div>


        <!-- 勤怠データ表示 -->
        <div class="attendance">
            <table class="attendance-record">
                <tr>
                    <th>名前</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>
                @foreach ($attendances as $attendance)
                <tr>
                    <td style="border-top: 1px solid #ccc;">{{ $attendance->user->name }}</td>
                    <td style="border-top: 1px solid #ccc;">
                        {{ $attendance->start_time ? \Carbon\Carbon::parse($attendance->start_time)->format('H:i') : '-' }}
                    </td>
                    <td style="border-top: 1px solid #ccc;">
                        {{ $attendance->end_time ? \Carbon\Carbon::parse($attendance->end_time)->format('H:i') : '-' }}
                    </td>
                    <td style="border-top: 1px solid #ccc;">
                        {{ sprintf('%02d:%02d', floor($attendance->break_minutes / 60), $attendance->break_minutes % 60) }}
                    </td>
                    <td style="border-top: 1px solid #ccc;">
                        {{ sprintf('%02d:%02d', floor($attendance->work_minutes / 60), $attendance->work_minutes % 60) }}
                    </td>
                    <td style="border-top: 1px solid #ccc;">
                        <a href="{{ route('admin_attendance', ['id' => $attendance->id]) }}">詳細</a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection