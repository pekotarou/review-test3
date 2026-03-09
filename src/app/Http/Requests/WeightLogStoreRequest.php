<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WeightLogStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => ['required', 'date'],
            'weight' => ['required', 'numeric', 'regex:/^\d{1,3}(\.\d)?$/'],
            'calories' => ['nullable', 'integer'],
            'exercise_time' => ['nullable', 'date_format:H:i'], // 修正: メール形式ではなく時刻
            'exercise_content' => ['nullable', 'string', 'max:120'],
        ];
    }
     public function messages(): array
    {
        return [
            'date.required' => '日付を入力してください',
            'weight.required' => '体重を入力してください',
            'weight.numeric' => '数字で入力してください',
            'weight.regex' => '4桁までの数字で小数点は1桁で入力してください',
            'calories.integer' => '数字で入力してください',
            'exercise_time.date_format' => '運動時間は HH:MM 形式で入力してください',
            'exercise_content.max' => '120文字以内で入力してください',
        ];
    }
}
