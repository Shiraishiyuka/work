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
            <span class="title-text">申請一覧3</span>
        </div>

        <div class="approval">
    <a href="{{ route('correctionrequest', ['status' => 'pending']) }}" 
       class="approval_text {{ request('status', 'pending') === 'pending' ? 'active' : '' }}">
       承認待ち
    </a>
    <a href="{{ route('correctionrequest', ['status' => 'approved']) }}" 
       class="approval_text {{ request('status', 'pending') === 'approved' ? 'active' : '' }}">
       承認済み
    </a>
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

                 @foreach($adjustments as $adjust)
                <tr>
                    <td>{{ $adjust->status === 'approved' ? '承認済み' : '承認待ち' }}</td>
                    <td>{{ $adjust->user->name }}</td>
                    <td>{{ $adjust->date->format('Y/m/d') }}</td> 
                    <td>{{ $adjust->remarks }}</td>
                    <td>{{ $adjust->created_at->format('Y/m/d') }}</td>
                    <td><a href="{{ route('approval', ['attendance_correct_request' => $adjust->id]) }}" class="plain-link">詳細</a></td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

@endsection









