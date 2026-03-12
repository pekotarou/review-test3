@extends('layouts.app')

@section('content')
<div class="modal-page">
    <!--背景に管理画面を表示-->
    @include('weight_logs._content')

    <!--半透明オーバーレイ-->
    <div class="modal-overlay">
        <div class="modal-card">
            <div class="modal-card__header">
                <h2 class="modal-card__title">Weight Logを追加</h2>
            </div>

            <form action="{{ route('weight_logs.store') }}" method="POST" class="weight-form">
                @csrf

                <div class="weight-form__group">
                    <label for="date" class="weight-form__label">日付
                        <span class="required-badge">必須</span>
                    </label>
                    <input
                        type="date"
                        id="date"
                        name="date"
                        value="{{ old('date', now()->format('Y-m-d')) }}"
                        class="weight-form__input"
                    >
                    @foreach ($errors->get('date') as $error)
                        <p class="weight-form__error">{{ $error }}</p>
                    @endforeach
                </div>

                <div class="weight-form__group">
                    <label for="weight" class="weight-form__label">体重
                        <span class="required-badge">必須</span>
                    </label>
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
                    @foreach ($errors->get('weight') as $error)
                        <p class="weight-form__error">{{ $error }}</p>
                    @endforeach
                </div>

                <div class="weight-form__group">
                    <label for="calories" class="weight-form__label">摂取カロリー
                        <span class="required-badge">必須</span>
                    </label>
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
                     @foreach ($errors->get('calories') as $error)
                        <p class="weight-form__error">{{ $error }}</p>
                    @endforeach
                </div>

                <div class="weight-form__group">
                    <label for="exercise_time" class="weight-form__label">運動時間
                        <span class="required-badge">必須</span>
                    </label>
                    <input
                        type="time"
                        id="exercise_time"
                        name="exercise_time"
                        value="{{ old('exercise_time') }}"
                        class="weight-form__input"
                    >
                    @foreach ($errors->get('exercise_time') as $error)
                        <p class="weight-form__error">{{ $error }}</p>
                    @endforeach


                </div>

                <div class="weight-form__group">
                    <label for="exercise_content" class="weight-form__label">運動内容</label>
                    <textarea
                        id="exercise_content"
                        name="exercise_content"
                        placeholder="運動内容を追加"
                        class="weight-form__textarea"
                    >{{ old('exercise_content') }}</textarea>
                    @foreach ($errors->get('exercise_content') as $error)
                        <p class="weight-form__error">{{ $error }}</p>
                    @endforeach
                </div>

                <div class="weight-form__buttons">
                    <a href="{{ route('weight_logs.index') }}" class="weight-form__button weight-form__button--back">
                        戻る
                    </a>

                    <button type="submit" class="weight-form__button weight-form__button--submit">
                        登録
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection