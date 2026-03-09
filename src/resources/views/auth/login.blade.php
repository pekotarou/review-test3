@extends('layouts.auth')

@section('content')
<div class="auth-card">
    <h1 class="logo">PiGLy</h1>
    <h2>ログイン</h2>

    <form action="{{ url('/login') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}">
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">パスワード</label>
            <input id="password" type="password" name="password">
            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary">ログイン</button>
    </form>

    <a href="{{ route('register.step1') }}" class="auth-link">アカウント作成はこちら</a>
</div>
@endsection