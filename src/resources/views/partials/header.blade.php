<div class="form">
    <form action="/" method="post">
        @csrf
        <input class="form_item" type="submit" value="勤怠" name="attendance">
        <input class="form_item" type="submit" value="勤怠一覧" name="attendance-list">
        <input class="form_item" type="submit" value="申請" name="request">
        @if (Auth::check())
        <!-- ログイン済みの場合、ログアウトボタンを表示 -->
        <input class="form_item" type="submit" value="ログアウト" name="logout">
        @else
        <!-- 未ログインの場合、ログインボタンを表示 -->
        <input class="form_item" type="submit" value="ログイン" name="login">
        @endif
    </form>
</div>