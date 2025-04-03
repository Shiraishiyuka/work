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
            <span class="title-text">勤怠一覧2</span>
        </div>

        <div class="indicate">
    <!-- 前月ボタン -->
<div class="previous-month">
    <a href="{{ route('attendance.list', ['year' => $month == 1 ? $year - 1 : $year, 'month' => $month == 1 ? 12 : $month - 1]) }}" class="previous-month_link">
        <div class="previous-month_image">
            <img src="{{ asset('storage/images/images.png') }}" class="images" alt="前月">
        </div>
        <div class="previous-month_text">前月</div>
    </a>
</div>

<!-- カレンダー画像（クリックでプルダウンメニューを表示） -->
<div class="calendar">
    <div class="calendar_image">
        <a href="#calendar-dropdown">  
            <img src="{{ asset('storage/images/download-1.png') }}" class="download" alt="カレンダー">
        </a>
    </div>
    <div class="calendar_text">{{ sprintf('%04d/%02d', $year, $month) }}</div>
</div>

<!-- 🔹 年月を選択するプルダウンメニュー -->
<div id="calendar-dropdown" class="calendar-popup">
    <form action="{{ route('attendance.list') }}" method="get">
        <label for="year">年：</label>
        <select name="year" id="year">
            @for ($y = date('Y') - 5; $y <= date('Y') + 5; $y++)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}年</option>
            @endfor
        </select>

        <label for="month">月：</label>
        <select name="month" id="month">
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ $m }}月</option>
            @endfor
        </select>

        <button type="submit">表示</button>
    </form>

    <a href="#" class="close-button">×</a>  <!-- 🔹 閉じるボタン -->
</div>

<!-- 次月ボタン -->
<div class="later-month">
    <a href="{{ route('attendance.list', ['year' => $month == 12 ? $year + 1 : $year, 'month' => $month == 12 ? 1 : $month + 1]) }}" class="later-month_link">
        <div class="later-month_image">
            <img src="{{ asset('storage/images/images.png') }}" class="images" alt="次月">
        </div>
        <div class="later-month_text">翌月</div>
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
            <td>{{ \Carbon\Carbon::parse($attendance->date)->format('m/d（D）') }}</td>
            <td>{{ $attendance->start_time ? \Carbon\Carbon::parse($attendance->start_time)->format('H:i') : '-' }}</td>
            <td>{{ $attendance->end_time ? \Carbon\Carbon::parse($attendance->end_time)->format('H:i') : '-' }}</td>
            <td>
                {{ sprintf('%02d:%02d', floor($attendance->break_minutes / 60), $attendance->break_minutes % 60) }}
            </td>
            <td>
                {{ sprintf('%02d:%02d', floor($attendance->work_minutes / 60), $attendance->work_minutes % 60) }}
            </td>
            <td>
                @if (!empty($attendance->id))
                    <a href="{{ route('attendancedetail', ['id' => $attendance->id]) }}">詳細</a>
                @else
                    <span>詳細なし</span>
                @endif
            </td>
        </tr>
        @if (!$loop->last)
        <tr><td colspan="6"><hr class="line"></td></tr>
        @endif
        @endforeach
    </table>
</div>
        
    </div>
</div>
@endsection