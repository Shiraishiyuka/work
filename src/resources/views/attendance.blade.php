@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}" />
<link rel="stylesheet" href="{{ asset('css/header.css') }}" />
@endsection

@section('header')
@include('partials.header')
@endsection

@section('content')
<div class="attendance-screen">
    <div class="attendance-screen_inner">
        <!-- 勤務状態を表示 -->
        <div class="work-status">
            
            @if($status === 'not_working')
                <div class="work-status_text">勤務外</div>
            @elseif($status === 'working')
                <div class="work-status_text">出勤中</div>
            @elseif($status === 'on_break')
                <div class="work-status_text">休憩中</div>
            @elseif($status === 'finished')
                <div class="work-status_text">退勤済</div>
            @endif
        </div>
        
        <!-- 現在の日付を表示 -->
        <div class="word-date">
            <div class="word-date_text">{{ $currentDateTime->format('Y年m月d日（D）') }}</div>
        </div>
        
        <!-- 現在の時刻を表示 -->
        <div class="work-time">
            <div class="work-time_text">{{ $currentDateTime->format('H:i') }}</div>
        </div>
        
        <!-- 勤務状態に応じたボタンを表示 -->
            @if($status === 'not_working')
                <!-- 出勤ボタン -->
                <form method="POST" action="{{ route('attendance.startWork') }}">
                    @csrf
                    <button type="submit" class="word-processing_text">出勤</button>
                </form>
            @elseif($status === 'working')
                <!-- 休憩入ボタンと退勤ボタン -->
                 <div class="word-processing_group">
                <form method="POST" action="{{ route('attendance.takeBreak') }}">
                    @csrf
                    
                    <div class="word-processing">
                    <button type="submit" class="word-processing_text">休憩入</button>
                    </div>
                </form>
                <form method="POST" action="{{ route('attendance.endWork') }}">
                    @csrf
                    <div class="word-processing">
                    <button type="submit" class="word-processing_text">退勤</button>
                    </div>
                    
                </form>
                </div>
            @elseif($status === 'on_break')
                <!-- 休憩戻ボタン -->
                <form method="POST" action="{{ route('attendance.endBreak') }}">
                    @csrf
                    <button type="submit" class="word-processing_text">休憩戻</button>
                </form>
            @elseif($status === 'finished')
                <!-- お疲れ様メッセージ -->
                <div class="text-good_job">お疲れ様でした！</div>
            @endif
        
    </div>
</div>
@endsection