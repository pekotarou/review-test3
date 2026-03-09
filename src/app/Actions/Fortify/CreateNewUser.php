<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\WeightLog;
use App\Models\WeightTarget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * 修正: STEP1 + STEP2 の全入力を受け取って、ユーザー関連データをまとめて作成
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'current_weight' => ['required', 'numeric', 'regex:/^\d{1,3}(\.\d)?$/'],
            'target_weight' => ['required', 'numeric', 'regex:/^\d{1,3}(\.\d)?$/'],
        ], [
            'name.required' => 'お名前を入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスは「ユーザー名@ドメイン」形式で入力してください',
            'email.unique' => 'このメールアドレスはすでに登録されています',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードは8文字以上で入力してください',
            'current_weight.required' => '現在の体重を入力してください',
            'current_weight.numeric' => '数字で入力してください',
            'current_weight.regex' => '4桁までの数字で小数点は1桁で入力してください',
            'target_weight.required' => '目標の体重を入力してください',
            'target_weight.numeric' => '数字で入力してください',
            'target_weight.regex' => '4桁までの数字で小数点は1桁で入力してください',
        ])->validate();

        return DB::transaction(function () use ($input) {
            // 修正: users テーブル作成
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']), // 修正: ハッシュ化
            ]);

            // 修正: 目標体重作成
            WeightTarget::create([
                'user_id' => $user->id,
                'target_weight' => $input['target_weight'],
            ]);

            // 修正: 初期体重ログ作成
            WeightLog::create([
                'user_id' => $user->id,
                'date' => now()->toDateString(),
                'weight' => $input['current_weight'],
                'calories' => null,
                'exercise_time' => null,
                'exercise_content' => null,
            ]);

            return $user;
        });
    }
}