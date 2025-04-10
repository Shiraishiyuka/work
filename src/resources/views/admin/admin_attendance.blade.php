@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance_detail.css') }}" />
<link rel="stylesheet" href="{{ asset('css/header.css') }}" />
@endsection

@section('header')
@include('partials.admin_header')
@endsection

@section('content')
<div class="attendance-detail_screen">
    <div class="attendance-detail_screen_inner">
        <div class="title-space">
            <div class="title-line"></div>
            <span class="title-text">勤怠詳細5</span>
        </div>

        <div class="attendance-detail_table">
            <form method="POST" action="{{ route('attendancedetail.update', ['id' => $attendance->id]) }}">
            @csrf

        @php
            $attendance = $adjust ?? $attendance;
        @endphp

            <table class="attendance-detail_data-table">
                <tr>
                    <th>名前</th>
                    <td>{{ $attendance->user->name }}</td>
                </tr>

                <tr>
                    <th>日付</th>
                    <td>
                        {{ date('Y年', strtotime($attendance->date)) }} {{ date('m月d日', strtotime($attendance->date)) }}
                        <input type="hidden" name="year" value="{{ date('Y', strtotime($attendance->date)) }}">
                        <input type="hidden" name="month_day" value="{{ date('m-d', strtotime($attendance->date)) }}">
                    </td>
                </tr>

                <tr>
                    <th>出勤・退勤</th>
                    <td>
                    <input type="text" name="start_time" class="attendance-detail_input"
                        value="{{ old('start_time', $attendance->start_time) }}"
                        {{ $hasPendingApproval ? 'disabled' : '' }}>
                    〜
                    <input type="text" name="end_time" class="attendance-detail_input"
                        value="{{ old('end_time', $attendance->end_time) }}"
                        {{ $hasPendingApproval ? 'disabled' : '' }}>

                    @error('start_time') <div class="form_error">{{ $message }}</div> @enderror
                    @error('end_time') <div class="form_error">{{ $message }}</div> @enderror
                    </td>
                </tr>

                <tr>
                    <th>休憩</th>
                    <td>
                        @foreach ($attendance->breakTimes as $index => $break)
                        <div class="break-time-row">
                            <input type="text" name="break_times[{{ $index }}][start_time]" class="attendance-detail_input"
                        value="{{ old("break_times.$index.start_time", $break->start_time) }}"
                        {{ $hasPendingApproval ? 'disabled' : '' }}>
                        〜
                        <input type="text" name="break_times[{{ $index }}][end_time]" class="attendance-detail_input"
                        value="{{ old("break_times.$index.end_time", $break->end_time) }}"
                        {{ $hasPendingApproval ? 'disabled' : '' }}>

                        @error("break_times.$index.start_time")
                            <div class="form_error">{{ $message }}</div>
                        @enderror

                        @error("break_times.$index.end_time")
                            <div class="form_error">{{ $message }}</div>
                        @enderror
                        </div>
                        @endforeach
                    </td>
                </tr>

                <tr>
                    <th>備考</th>
                    <td>
                        <textarea name="remarks" class="attendance-detail_textarea" {{ $hasPendingApproval ? 'disabled' : '' }}>
                        {{ old('remarks', $attendance->remarks) }}
                        </textarea>
                        @error('remarks') <div class="form_error">{{ $message }}</div> @enderror
                    </td>
                </tr>
            </table>
        </div>

        <div class="button-group">
        @if ($hasPendingApproval)
            <p style="color: red;" class="button-text">＊承認待ちのため修正できません</p>
        @else
            <button type="submit" class="button-submit">修正</button>
        @endif
        </form>
        </div>
    </div>
</div>
@endsection