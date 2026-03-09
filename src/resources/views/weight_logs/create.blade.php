@extends('layouts.app')

@section('content')
<div class="weight-form-page">
    <div class="weight-form-card">
        <div class="weight-form-card__header">
            <h2 class="weight-form-card__title">Weight Logを追加</h2>
        </div>

        <form action="{{ route('weight_logs.store') }}" method="POST" class="weight-form">
            @csrf

            {{-- 修正: 日付 --}}
            <div class="weight-form__group">
                <label for="date" class="weight-form__label">日付</label>
                <input
                    type="date"
                    id="date"
                    name="date"
                    value="{{ old('date', now()->format('Y-m-d')) }}"
                    class="weight-form__input"
                >
                @error('date')
                    <p class="weight-form__error">{{ $message }}</p>
                @enderror
            </div>

            {{-- 修正: 体重 --}}
            <div class="weight-form__group">
                <label for="weight" class="weight-form__label">体重</label>
                <div class="weight-form__input-unit">
                    <input
                        type="text"
                        id="weight"
                        name="weight"
                        value="{{ old('weight') }}"
                        placeholder="50.0"
                        class="weight-form__input"
                    >
                    <span class="weight-form__unit">kg</span>
                </div>
                @error('weight')
                    <p class="weight-form__error">{{ $message }}</p>
                @enderror
            </div>

            {{-- 修正: 摂取カロリー --}}
            <div class="weight-form__group">
                <label for="calories" class="weight-form__label">摂取カロリー</label>
                <div class="weight-form__input-unit">
                    <input
                        type="text"
                        id="calories"
                        name="calories"
                        value="{{ old('calories') }}"
                        placeholder="1200"
                        class="weight-form__input"
                    >
                    <span class="weight-form__unit">cal</span>
                </div>
                @error('calories')
                    <p class="weight-form__error">{{ $message }}</p>
                @enderror
            </div>

            {{-- 修正: 運動時間 --}}
            <div class="weight-form__group">
                <label for="exercise_time" class="weight-form__label">運動時間</label>
                <input
                    type="time"
                    id="exercise_time"
                    name="exercise_time"
                    value="{{ old('exercise_time') }}"
                    class="weight-form__input"
                >
                @error('exercise_time')
                    <p class="weight-form__error">{{ $message }}</p>
                @enderror
            </div>

            {{-- 修正: 運動内容 --}}
            <div class="weight-form__group">
                <label for="exercise_content" class="weight-form__label">運動内容</label>
                <textarea
                    id="exercise_content"
                    name="exercise_content"
                    placeholder="運動内容を追加"
                    class="weight-form__textarea"
                >{{ old('exercise_content') }}</textarea>
                @error('exercise_content')
                    <p class="weight-form__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="weight-form__buttons">
                {{-- 修正: 戻る --}}
                <a href="{{ route('weight_logs.index') }}" class="weight-form__button weight-form__button--back">
                    戻る
                </a>

                {{-- 修正: 登録 --}}
                <button type="submit" class="weight-form__button weight-form__button--submit">
                    登録
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
