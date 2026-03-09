<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PiGLy</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/weight_logs.css') }}">
</head>
<body>
    <header class="header">
        <div class="header__inner">
            <h1 class="header__logo">PiGLy</h1>

            <div class="header__nav">
                {{-- 修正: 目標設定ページへのリンク --}}
                <a href="{{ route('goal_setting.edit') }}" class="header__link">目標体重設定</a>

                {{-- 修正: ログアウト --}}
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="header__button">ログアウト</button>
                </form>
            </div>
        </div>
    </header>

    <main class="main">
        @yield('content')
    </main>
</body>
</html>