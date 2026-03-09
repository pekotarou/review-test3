<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GoalSettingRequest;
use Illuminate\Support\Facades\Auth;

class GoalSettingController extends Controller
{
    public function edit()
    {
        $target = Auth::user()->weightTarget;

        return view('weight_logs.goal-setting', compact('target'));
    }

    public function update(GoalSettingRequest $request)
    {
        Auth::user()->weightTarget()->updateOrCreate(
            ['user_id' => Auth::id()],
            ['target_weight' => $request->target_weight]
        );

        return redirect()->route('weight_logs.index');
    }
}
