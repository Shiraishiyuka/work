@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance_detail.css') }}" />
<link rel="stylesheet" href="{{ asset('css/header.css') }}" />
@endsection

@section('header')
@include('partials.header')
@endsection


@section('content')
<div class="attendance-detail_screen">
    <div class="attendance-detail_screen_inner">
        <div class="title-space">
            <div class="title-line"></div>
            <span class="title-text">勤怠詳細</span>
        </div>

        <div class="attendance-detail_table">
            <div class="attendance-detail_row">
                <div class="attendance-detail_text">名前</div>
                <div class="attendance-detail_form-text">西玲奈</div>
            </div>
            <div class="attendance-detail_row">
                <div class="attendance-detail_text">日付</div>
                <div class="attendance-detail_form-text">2023年</div>
                <div class="attendance-detail_form-text">6月1日</div>
            </div>
            <div class="attendance-detail_row">
                <div class="attendance-detail_text">出勤・退勤</div>
                <div class="attendance-detail_form-text">12:00</div>
                <div class="attendance-detail_form-text">13:00</div>
            </div>
            <div class="attendance-detail_row">
                <div class="attendance-detail_text">休憩</div>
                <div class="attendance-detail_form-text">12:00</div>
                <div class="attendance-detail_form-text">13:00</div>
            </div>
            <div class="attendance-detail_row">
                <div class="attendance-detail_text">備考</div>
                <div class="attendance-detail_form-text">電車遅延のため</div>
            </div>
        </div>

        <div class="button-group">
        <!-- 修正ボタン -->
            <div class="button">
                <button class="button-submit">修正する</button>
            </div>
        </div>

    </div>
</div>

@END