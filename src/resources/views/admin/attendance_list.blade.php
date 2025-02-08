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
            <span class="title-text">{{ $currentDate->translatedFormat('Y年m月d日（D）') }}</span>
        </div>

        <div class="indicate">
            <!-- 前日ボタン -->
            <div class="previous-day">
                <a href="{{ route('admin.attendance.list', ['date' => $previousDate]) }}">
                    <div class="previous-day_image">
                        <img src="{{ asset('storage/images/images.png') }}" class="images" alt="前日">
                    </div>
                    <div class="previous-day_text">前日</div>
                </a>
            </div>

            <!-- カレンダー表示 -->
            <div class="calendar">
                <div class="calendar_image">
                    <a href="{{ route('admin.attendance.list', ['date' => $currentDate->toDateString()]) }}">
                        <img src="{{ asset('storage/images/download-1.png') }}" class="download" alt="カレンダー">
                    </a>
                </div>
                <div class="calendar_text">{{ $currentDate->format('Y/m/d') }}</div>
            </div>

            <!-- 次日ボタン -->
            <div class="next-day">
                <a href="{{ route('admin.attendance.list', ['date' => $nextDate]) }}">
                    <div class="next-day_image">
                        <img src="{{ asset('storage/images/images.png') }}" class="images" alt="次日">
                    </div>
                    <div class="next-day_text">次日</div>
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
                        <td>{{ $attendance->user->name }}</td>
                        <td>{{ $attendance->start_time }}</td>
                        <td>{{ $attendance->end_time ?? '-' }}</td>
                        <td>{{ floor($attendance->break_minutes / 60) }}時間{{ $attendance->break_minutes % 60 }}分</td>
                        <td>{{ floor($attendance->work_minutes / 60) }}時間{{ $attendance->work_minutes % 60 }}分</td>
                        <td><a href="{{ route('attendancedetail', ['id' => $attendance->id]) }}">詳細</a></td>
                    </tr>
                    @endforeach
                </table>
            </div>
    </div>
</div>
@endsection