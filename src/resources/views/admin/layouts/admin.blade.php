<!--<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>work</title>

    @yield('css')

    <style>
            * {
                margin: 0;
                padding: 0;
            }

            html, body {
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
}

            .header {
                width: 100%;
                background-color: #000000;
                height: 10%;
            }

            .header__inner {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .logo {
                width: 300px;

            }

            main {
                width: 100%;
                height: 90%;
                
            }

            
    </style>

</head>
<body>
    <header class="header">
        <div class="header__inner">
            <a href="logo"><img src="{{ asset('storage/images/logo.svg') }}" class="logo" alt="Logo"></a>

            @include('partials.admin_header')  {{-- 管理者用ヘッダー --}}
        </div>
        
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>-->