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
    <div class="correction_screen_inner">
        <div class="title-space">
            <div class="title-line"></div>
            <span class="title-text">申請一覧</span>
        </div>

        <div class="approval">
            <div class="approval_text">承認待ち</div>
            <div class="approval_text">承認済み</div>
        </div>

        <div class="petition">
            <table class="petition-table">
                <tr>
                    <th>状態</th>
                    <th>名前</th>
                    <th>対象日時</th>
                    <th>申請理由</th>
                    <th>申請日時</th>
                    <th>詳細</th>
                </tr>

                <tr>
                    <td>承認待ち</td>
                    <td>西玲奈</td>
                    <td>2023/06/01</td>
                    <td>遅延のため</td>
                    <td>2023/06/02</td>
                    <td>詳細</td>
                </tr>
            </table>
        </div>
    </div>
</div>

@endsection
