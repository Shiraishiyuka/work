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
            <span class="title-text">{{ $user->name }} さんの勤怠</span>
        </div>

        <div class="indicate">
        <!-- 前月ボタン -->
            <div class="previous-month">
                <a href="{{ route('by_staff', [
                'id' => $user->id,
                'year' => ($month == 1) ? ($year - 1) : $year,
                'month' => ($month == 1) ? 12 : ($month - 1)
                ]) }}" class="previous-month_link">
                    <div class="previous-month_image">
                        <img src="{{ asset('storage/images/images.png') }}" class="images" alt="前月">
                    </div>
                    <div class="previous-month_text">前月</div>
                </a>
            </div>

        <!-- カレンダー表示 -->
            <div class="calendar">
                <div class="calendar_image">
                    <a href="{{ route('by_staff', [
                    'id' => $user->id,
                    'year' => $year,
                    'month' => $month
                    ]) }}">
                    <img src="{{ asset('storage/images/download-1.png') }}" class="download" alt="カレンダー">
                    </a>
                </div>
            <div class="calendar_text">{{ sprintf('%04d/%02d', $year, $month) }}</div>
        </div>

        <!-- 次月ボタン -->
        <div class="later-month">
            <a href="{{ route('by_staff', [
            'id' => $user->id,
            'year' => ($month == 12) ? ($year + 1) : $year,
            'month' => ($month == 12) ? 1 : ($month + 1)
            ]) }}" class="later-month_link">
                <div class="later-month_image">
                    <img src="{{ asset('storage/images/images.png') }}" class="images" alt="次月">
                </div>
                <div class="later-month_text">次月</div>
            </a>
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
            @foreach ($attendances as $attendance)
            <tr>
                <td>{{ $attendance->date }}</td>
                <td>{{ $attendance->start_time }}</td>
                <td>{{ $attendance->end_time ?? '-' }}</td>
                <td>{{ floor($attendance->break_minutes / 60) }}時間{{ $attendance->break_minutes % 60 }}分</td>
                <td>{{ floor($attendance->work_minutes / 60) }}時間{{ $attendance->work_minutes % 60 }}分</td>
                <td><a href="{{ route('admin', ['id' => $attendance->id]) }}">詳細</a></td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="export-button">
        <a href="{{ route('by_staff.csv', ['id' => $user->id, 'year' => $year, 'month' => $month]) }}" class="csv-button">CSV出力
        </a>
    </div>
    </div>
</div>
@endsection