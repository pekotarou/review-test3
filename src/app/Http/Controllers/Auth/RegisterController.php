<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterStep1Request;
use App\Http\Requests\RegisterStep2Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function createStep1()
    {
        return view('auth.register-step1');
    }

    public function storeStep1(RegisterStep1Request $request)
    {
        // 修正: STEP1 の入力をセッションへ保存
        session([
            'register_step1' => $request->only(['name', 'email', 'password']),
        ]);

        return redirect()->route('register.step2');
    }

    public function createStep2()
    {
        if (!session()->has('register_step1')) {
            return redirect()->route('register.step1');
        }

        return view('auth.register-step2');
    }

    public function storeStep2(RegisterStep2Request $request, CreateNewUser $createNewUser)
    {
        $step1 = session('register_step1');

        if (!$step1) {
            return redirect()->route('register.step1');
        }

        // 修正: ユーザー作成処理は Fortify の Action に集約
        $user = $createNewUser->create([
            'name' => $step1['name'],
            'email' => $step1['email'],
            'password' => $step1['password'],
            'current_weight' => $request->current_weight,
            'target_weight' => $request->target_weight,
        ]);

        session()->forget('register_step1');

        // 修正: 作成後はログイン状態にする
        Auth::login($user);

        return redirect()->route('weight_logs.index');
    }
}