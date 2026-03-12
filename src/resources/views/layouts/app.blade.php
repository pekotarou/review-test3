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
                <!--目標設定ページへのリンク-->
                <a href="{{ route('goal_setting.edit') }}" class="header__link">
                    <img src="{{ asset('images/icon-setting.png') }}" alt="" class="header__icon">
                    <span>目標体重設定</span>
                </a>

                <!--左側にログアウトアイコン画像を追加-->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="header__button">
                        <img src="{{ asset('images/icon-logout.png') }}" alt="" class="header__icon">
                        <span>ログアウト</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="main">
        @yield('content')
    </main>
</body>
</html>