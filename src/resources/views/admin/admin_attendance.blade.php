@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin_attendance.css') }}" />
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
            <span class="title-text">管理者用 勤怠詳細</span>
        </div>

        <div class="attendance-detail_table">
            <form method="POST" action="{{ route('admin.update', ['id' => $attendance->id]) }}">
                @csrf

                <div class="attendance-detail_row">
                    <div class="attendance-detail_text">名前</div>
                    <div class="attendance-detail_form-text">{{ $attendance->user->name }}</div>
                </div>

                <div class="attendance-detail_row">
                    <div class="attendance-detail_text">勤務日</div>
                    <div class="attendance-detail_form-text">
                        <select name="year">
                            @for ($i = 2020; $i <= now()->year; $i++)
                                <option value="{{ $i }}" {{ $i == date('Y', strtotime($attendance->date)) ? 'selected' : '' }}>{{ $i }}年</option>
                            @endfor
                        </select>
                        <select name="month_day">
                            @for ($i = 1; $i <= 12; $i++)
                                @for ($j = 1; $j <= 31; $j++)
                                    @php
                                        $dateStr = sprintf('%02d-%02d', $i, $j);
                                        $selected = ($dateStr == date('m-d', strtotime($attendance->date))) ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $dateStr }}" {{ $selected }}>{{ $i }}月{{ $j }}日</option>
                                @endfor
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="attendance-detail_row">
                    <div class="attendance-detail_text">出勤時間</div>
                    <input type="text" name="start_time" class="attendance-detail_form-text"
                           value="{{ old('start_time', $attendance->start_time) }}">
                    @error('start_time')
                        <div class="form_error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="attendance-detail_row">
                    <div class="attendance-detail_text">退勤時間</div>
                    <input type="text" name="end_time" class="attendance-detail_form-text"
                           value="{{ old('end_time', $attendance->end_time) }}">
                    @error('end_time')
                        <div class="form_error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="attendance-detail_row">
                    <div class="attendance-detail_text">休憩開始時間</div>
                    <input type="text" name="break_start_time" class="attendance-detail_form-text"
                           value="{{ old('break_start_time', $attendance->break_start_time) }}">
                    @error('break_start_time')
                        <div class="form_error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="attendance-detail_row">
                    <div class="attendance-detail_text">休憩終了時間</div>
                    <input type="text" name="break_end_time" class="attendance-detail_form-text"
                           value="{{ old('break_end_time', $attendance->break_end_time) }}">
                    @error('break_end_time')
                        <div class="form_error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="attendance-detail_row">
                    <div class="attendance-detail_text">休憩時間 (分)</div>
                    <input type="number" name="break_minutes" class="attendance-detail_form-text"
                    value="{{ old('break_minutes', $break_minutes) }}" readonly>
                    @error('break_minutes')
                        <div class="form_error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="attendance-detail_row">
                    <div class="attendance-detail_text">備考</div>
                    <div class="attendance-detail_form-text">
                        <textarea name="remarks">{{ old('remarks', $attendance->remarks) }}</textarea>
                        @error('remarks')
                            <div class="form_error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="button-submit">修正</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection