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


        <div class="word-date">
            <div class="word-date_text">
                {{ $currentDateTime->translatedFormat('Y年m月d日（D）') }}
            </div>
        </div>


        <div class="work-time">
            <div class="work-time_text">
                {{ $currentDateTime->format('H:i') }}
            </div>
        </div>
        
            @if($status === 'not_working')

                <form method="POST" action="{{ route('attendance.startWork') }}">
                    @csrf
                    <div class="word-processing">
                        <button type="submit" class="word-processing_text">出勤</button>
                    </div>
                </form>
            @elseif($status === 'working')

            <div class="word-processing_group">
                <form method="POST" action="{{ route('attendance.endWork') }}">
                @csrf
                <div class="word-processing">
                    <button type="submit" class="word-processing_text">退勤</button>
                </div>
                </form>
                <form method="POST" action="{{ route('attendance.takeBreak') }}">
                @csrf
            <div class="break-processing">
                <button type="submit" class="word-processing_text break-button">休憩入</button>
            </div>
            </form>
        </div>
            @elseif($status === 'on_break')

                <form method="POST" action="{{ route('attendance.endBreak') }}">
                    @csrf
                    <div class="break-processing">
                        <button type="submit" class="word-processing_text break-button">休憩戻</button>
                    </div>
                </form>
            @elseif($status === 'finished')

                <div class="text-good_job">お疲れ様でした！</div>
            @endif
        
    </div>
</div>
@endsection
