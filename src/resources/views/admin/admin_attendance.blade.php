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
    <form action="{{ route('admin.attendance.update', ['id' => $attendance->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="user_id" value="{{ $attendance->user_id }}"> <!-- `user_id` を渡す -->
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
            <tr>
                <td>{{ $attendance->user->name }}</td>
            <td>{{ $attendance->date }}</td>
            <td><input type="time" name="start_time" value="{{ old('start_time', $attendance->start_time) }}"></td>
            <td><input type="time" name="end_time" value="{{ old('end_time', $attendance->end_time) }}"></td>
            <td><input type="number" name="break_minutes" value="{{ old('break_minutes', $attendance->break_minutes) }}"></td>
            <td><input type="text" name="remarks" value="{{ old('remarks', $attendance->remarks) }}"></td>
            </tr>
        </tr>
    </table>

    <div class="button">
        <button type="submit" class="button-submit">修正</button>
    </div>
    </form>
</div>

@endsection
