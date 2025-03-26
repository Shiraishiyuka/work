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
            <span class="title-text">勤怠詳細</span>
        </div>

        <div class="attendance-detail_table">
            <form method="POST" action="{{ route('attendancedetail.update', ['id' => $attendance->id]) }}">
                @csrf

                <div class="attendance-detail_row">
                    <div class="attendance-detail_text">名前</div>
                    <div class="attendance-detail_form-text">{{ $attendance->user->name }}</div>
                </div>

                <div class="attendance-detail_row">
                    <div class="attendance-detail_text">日付</div>
                    <div class="attendance-detail_form-text">
                        {{ date('Y年', strtotime($attendance->date)) }}
                    </div>
                    <div class="attendance-detail_form-text">
                        {{ date('m月d日', strtotime($attendance->date)) }}
                    </div>
                    <!-- 変更できないように hidden で送信 -->
                    <input type="hidden" name="year" value="{{ date('Y', strtotime($attendance->date)) }}">
                    <input type="hidden" name="month_day" value="{{ date('m-d', strtotime($attendance->date)) }}">
                </div>

                <div class="attendance-detail_row">
                    <div class="attendance-detail_text">出勤・退勤</div>
                    <div class="attendance-detail_form-group">
                        <input type="text" name="start_time" class="attendance-detail_form-text"
                               value="{{ old('start_time', $adjust->start_time ?? $attendance->start_time) }}" 
       {{ $hasPendingApproval ? 'disabled' : '' }}>
                        @error('start_time')
                            <div class="form_error">{{ $message }}</div>
                        @enderror
                    </div>
                    <span>〜</span>
                    <div class="attendance-detail_form-group">
                        <input type="text" name="end_time" class="attendance-detail_form-text"
                               value="{{ old('end_time', $adjust->end_time ?? $attendance->end_time) }}" 
       {{ $hasPendingApproval ? 'disabled' : '' }}>
                        @error('end_time')
                            <div class="form_error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="attendance-detail_row">
                    <div class="attendance-detail_text">休憩</div>
                    <div class="attendance-detail_form-group">
                        <input type="text" name="break_start_time" class="attendance-detail_form-text"
                               value="{{ old('break_start_time', $adjust->break_start_time ?? $attendance->break_start_time) }}" 
       {{ $hasPendingApproval ? 'disabled' : '' }}>
                        @error('break_start_time')
                            <div class="form_error">{{ $message }}</div>
                        @enderror
                    </div>
                    <span>〜</span>
                    <div class="attendance-detail_form-group">
                        <input type="text" name="break_end_time" class="attendance-detail_form-text"
                                value="{{ old('break_end_time', $adjust->break_end_time ?? $attendance->break_end_time) }}" 
       {{ $hasPendingApproval ? 'disabled' : '' }}>
                        @error('break_end_time')
                            <div class="form_error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="attendance-detail_row">
                    <div class="attendance-detail_textarea">備考</div>
                    <div class="attendance-detail_form-text">
                        <textarea name="remarks" {{ $hasPendingApproval ? 'disabled' : '' }}>
                            {{ old('remarks', $adjust->remarks ?? $attendance->remarks) }}
                        </textarea>
                        @error('remarks')
                            <div class="form_error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="button-group">
                    @if ($hasPendingApproval)
                        <p style="color: red;" class="button-text">＊承認待ちのため修正できません</p>
                    @else
                        <button type="submit" class="button-submit">修正</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection