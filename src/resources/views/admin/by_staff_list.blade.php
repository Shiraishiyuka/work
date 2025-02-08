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
            <span class="title-text">{{ $user->name }} さんの勤怠</span>
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
                        <td><a href="{{ route('attendancedetail', ['id' => $attendance->id]) }}">詳細</a></td>
                    </tr>
                    @endforeach
                </table>
            </div>
    </div>
</div>
@endsection