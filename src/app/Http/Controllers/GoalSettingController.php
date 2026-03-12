<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GoalSettingRequest;
use Illuminate\Support\Facades\Auth;

class GoalSettingController extends Controller
{
    //目標体重設定ボタンを押した時の動き
    public function edit()
    {
        //ログインしている人の目標体重データ
        $target = Auth::user()->weightTarget;
        //目標体重変更画面を表示
        return view('weight_logs.goal-setting', compact('target'));
    }
    //目標体重を入力後に更新ボタンを押した後の動き
    public function update(GoalSettingRequest $request)
    {
        //ログイン中ユーザーの目標体重データをuser_idで探してあればtarget_weightを更新し、なければ新しく作る
        Auth::user()->weightTarget()->updateOrCreate(
            ['user_id' => Auth::id()],
            ['target_weight' => $request->target_weight]
        );
        //体重管理画面表示
        return redirect()->route('weight_logs.index');
    }
}
