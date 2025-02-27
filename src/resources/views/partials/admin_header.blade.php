<div class="form">
    <form action="{{ route('admin.attendance.list') }}" method="post">
        @csrf
        <input class="form_item" type="submit" value="勤怠一覧" name="admin_attendance">
        <input class="form_item" type="submit" value="スタッフ一覧" name="staff">
        <input class="form_item" type="submit" value="申請一覧" name="admin_request">
        @if (Auth::check())
        <!-- ログイン済みの場合、ログアウトボタンを表示 -->
        <input class="form_item" type="submit" value="ログアウト" name="admin_logout">
        @else
        <!-- 未ログインの場合、ログインボタンを表示 -->
        <input class="form_item" type="submit" value="ログイン" name="admin_login">
        @endif
    </form>
</div>