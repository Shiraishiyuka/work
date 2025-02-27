@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/correctionrequest.css') }}" />
<link rel="stylesheet" href="{{ asset('css/header.css') }}" />
@endsection

@section('header')
@include('partials.admin_header')
@endsection

@section('content')
<div class="correction_screen">
    <div class="correction_screen_inner">
        <div class="title-space">
            <div class="title-line"></div>
            <span class="title-text">申請一覧</span>
        </div>

        <div class="indicate">
            <!-- 前月ボタン -->
            <div class="previous-month">
                <a href="{{ route('admin.application_request', ['year' => $previousYear, 'month' => $previousMonth]) }}">
                    <div class="previous-month_image">
                        <img src="{{ asset('storage/images/images.png') }}" class="images" alt="前月">
                    </div>
                    <div class="previous-month_text">前月</div>
                </a>
            </div>

            <!-- カレンダー表示 -->
            <div class="calendar">
                <div class="calendar_image">
                    <a href="{{ route('admin.application_request', ['year' => $currentDate->year, 'month' => $currentDate->month]) }}">
                        <img src="{{ asset('storage/images/download-1.png') }}" class="download" alt="カレンダー">
                    </a>
                </div>
                <div class="calendar_text">{{ $currentDate->format('Y/m') }}</div>
            </div>

            <!-- 次月ボタン -->
            <div class="next-month">
                <a href="{{ route('admin.application_request', ['year' => $nextYear, 'month' => $nextMonth]) }}">
                    <div class="next-month_image">
                        <img src="{{ asset('storage/images/images.png') }}" class="images" alt="次月">
                    </div>
                    <div class="next-month_text">次月</div>
                </a>
            </div>
        </div>

        <div class="petition">
            <div class="table-container">
                <table class="petition-table">
                    <tr>
                        <th>状態</th>
                        <th>名前</th>
                        <th>対象日時</th>
                        <th>申請理由</th>
                        <th>申請日時</th>
                        <th>詳細</th>
                    </tr>

                    @foreach($adjustments as $adjust)
                    <tr>
                        <td>{{ $adjust->status === 'approved' ? '承認済み' : '承認待ち' }}</td>
                        <td>{{ $adjust->user->name }}</td>
                        <td>{{ $adjust->original_date }}  {{ $adjust->date }}</td> 
                        <td>{{ $adjust->remarks }}</td>
                        <td>{{ $adjust->created_at->format('Y/m/d') }}</td>
                        <td><a href="{{ route('approval', ['attendance_correct_request' => $adjust->id]) }}">詳細</a></td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection