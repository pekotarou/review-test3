<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WeightLogSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_date' => ['nullable', 'date'], // 修正: 開始日は未入力OK、入力されたら日付形式
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'], // 修正: 終了日は開始日以降
        ];
    }

    public function messages(): array
    {
        return [
            'start_date.date' => '開始日は正しい日付を入力してください',
            'end_date.date' => '終了日は正しい日付を入力してください',
            'end_date.after_or_equal' => '終了日は開始日以降の日付を入力してください',
        ];
    }
}