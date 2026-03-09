<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\WeightLogStoreRequest;
use App\Http\Requests\WeightLogUpdateRequest;
use App\Models\WeightLog;
use Illuminate\Support\Facades\Auth;

class WeightLogController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $weightLogs = $user->weightLogs()
            ->latest('date')
            ->paginate(8);

        $latestLog = $user->weightLogs()->latest('date')->first();
        $target = $user->weightTarget;

        $currentWeight = $latestLog?->weight;
        $targetWeight = $target?->target_weight;
        $diffWeight = ($currentWeight !== null && $targetWeight !== null)
            ? round($currentWeight - $targetWeight, 1)
            : null;

        return view('weight_logs.index', compact(
            'weightLogs',
            'currentWeight',
            'targetWeight',
            'diffWeight'
        ));
    }

    public function search(Request $request)
    {
        $user = Auth::user();

        $query = $user->weightLogs()->newQuery();
        $query->where('user_id', $user->id);

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $weightLogs = $query->latest('date')->paginate(8)->appends($request->query());

        $latestLog = $user->weightLogs()->latest('date')->first();
        $target = $user->weightTarget;

        $currentWeight = $latestLog?->weight;
        $targetWeight = $target?->target_weight;
        $diffWeight = ($currentWeight !== null && $targetWeight !== null)
            ? round($currentWeight - $targetWeight, 1)
            : null;

        return view('weight_logs.index', compact(
            'weightLogs',
            'currentWeight',
            'targetWeight',
            'diffWeight'
        ));
    }

    public function create()
    {
        return view('weight_logs.create');
    }

    public function store(WeightLogStoreRequest $request)
    {
        Auth::user()->weightLogs()->create($request->validated());

        return redirect()->route('weight_logs.index');
    }

    public function show(WeightLog $weightLog)
    {
        $this->authorizeLog($weightLog);

        return view('weight_logs.edit', compact('weightLog'));
    }

    public function edit(WeightLog $weightLog)
    {
        $this->authorizeLog($weightLog);

        return view('weight_logs.edit', compact('weightLog'));
    }

    public function update(WeightLogUpdateRequest $request, WeightLog $weightLog)
    {
        $this->authorizeLog($weightLog);

        $weightLog->update($request->validated());

        return redirect()->route('weight_logs.index');
    }

    public function destroy(WeightLog $weightLog)
    {
        $this->authorizeLog($weightLog);

        $weightLog->delete();

        return redirect()->route('weight_logs.index');
    }

    private function authorizeLog(WeightLog $weightLog): void
    {
        abort_if($weightLog->user_id !== Auth::id(), 403);
    }
}
