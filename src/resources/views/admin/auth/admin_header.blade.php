<!--<div class="form">
    <form action="{{ route('admin.attendance.list') }}" method="post">
        @csrf
        <input class="form_item" type="submit" value="勤怠一覧" name="attendance">
        <input class="form_item" type="submit" value="スタッフ一覧" name="attendance-list">
        <input class="form_item" type="submit" value="申請一覧" name="request">
        @if (Auth::check())
        <input class="form_item" type="submit" value="ログアウト" name="logout">
        @else

        <input class="form_item" type="submit" value="ログイン" name="login">
        @endif
    </form>
</div>-->