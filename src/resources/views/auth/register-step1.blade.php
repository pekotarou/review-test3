@extends('layouts.auth')

@section('content')
<div class="auth-card">
    <h1 class="logo">PiGLy</h1>
    <h2>新規会員登録</h2>
    <p class="step">STEP1 アカウント情報の登録</p>

    <form action="{{ route('register.step1.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>お名前</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}">
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>パスワード</label>
            <input type="password" name="password">
            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary">次に進む</button>
    </form>

    <a href="{{ url('/login') }}" class="auth-link">ログインはこちら</a>
</div>
@endsection