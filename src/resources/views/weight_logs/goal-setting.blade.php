@extends('layouts.app')

@section('content')
<div class="goal-setting-page">
    <div class="goal-setting-card">
        <div class="goal-setting-card__header">
            <h2 class="goal-setting-card__title">目標体重設定</h2>
        </div>

        <form action="{{ route('goal_setting.update') }}" method="POST" class="goal-setting-form">
            @csrf
            @method('PUT')

            <div class="goal-setting-form__group">
                <label for="target_weight" class="goal-setting-form__label">目標体重</label>

                <div class="goal-setting-form__input-unit">
                    <input
                        type="text"
                        id="target_weight"
                        name="target_weight"
                        value="{{ old('target_weight', optional($target)->target_weight !== null ? number_format($target->target_weight, 1, '.', '') : '') }}"
                        placeholder="50.0"
                        class="goal-setting-form__input"
                    >
                    <span class="goal-setting-form__unit">kg</span>
                </div>

                @error('target_weight')
                    <p class="goal-setting-form__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="goal-setting-form__buttons">
                <a href="{{ route('weight_logs.index') }}" class="goal-setting-form__button goal-setting-form__button--back">
                    戻る
                </a>

                <button type="submit" class="goal-setting-form__button goal-setting-form__button--submit">
                    更新
                </button>
            </div>
        </form>
    </div>
</div>
@endsection