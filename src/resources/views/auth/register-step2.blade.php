@extends('layouts.auth')

@section('content')
<div class="auth-card">
    <h1 class="logo">PiGLy</h1>
    <h2>新規会員登録</h2>
    <p class="step">STEP2 体重データの入力</p>

    <form action="{{ route('register.step2.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>現在の体重</label>
            <div class="input-unit">
                <input type="text" name="current_weight" value="{{ old('current_weight') }}">
                <span>kg</span>
            </div>
            @error('current_weight')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>目標の体重</label>
            <div class="input-unit">
                <input type="text" name="target_weight" value="{{ old('target_weight') }}">
                <span>kg</span>
            </div>
            @error('target_weight')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary">アカウント作成</button>
    </form>
</div>
@endsection