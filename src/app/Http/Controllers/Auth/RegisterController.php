<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterStep1Request;
use App\Http\Requests\RegisterStep2Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    //会員登録ステップ１表示
    public function createStep1()
    {
        return view('auth.register-step1');
    }

    public function storeStep1(RegisterStep1Request $request)
    {
        //ステップ１の入力情報をセッションへ保存
        session([
            'register_step1' => $request->only(['name', 'email', 'password']),
        ]);
        //ステップ２を表示
        return redirect()->route('register.step2');
    }
    //会員登録ステップ２
    public function createStep2()
    {
        //ステップ１のセッション情報がなければ、ステップ１に戻る
        if (!session()->has('register_step1')) {
            return redirect()->route('register.step1');
        }
        //ステップ２のviewを表示
        return view('auth.register-step2');
    }

    //ステップ2のボタンが押された際の動き
    public function storeStep2(RegisterStep2Request $request, CreateNewUser $createNewUser)
    {
        $step1 = session('register_step1');
        //もしステップ１のセッション情報がなければ、ステップ１に戻る
        if (!$step1) {
            return redirect()->route('register.step1');
        }

        //ユーザー作成処理はApp\Actions\Fortify\CreateNewUserで行う
        $user = $createNewUser->create([
            'name' => $step1['name'],
            'email' => $step1['email'],
            'password' => $step1['password'],
            'current_weight' => $request->current_weight,
            'target_weight' => $request->target_weight,
        ]);

        //ステップ１のセッション情報を削除する
        session()->forget('register_step1');

        //作成後はログイン状態にする
        Auth::login($user);

        //WeightLogControllerの'index'に進む（体重管理画面へ進む）
        return redirect()->route('weight_logs.index');
    }
}