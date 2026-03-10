<?php

namespace App\Http\Controllers;

use App\Http\Requests\WeightLogSearchRequest;
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

        //検索判定用
        $isSearching = false; // 修正: 通常一覧では検索中ではない
        $searchSummary = null; // 修正: 通常一覧では検索文言なし


        return view('weight_logs.index', compact(
            'weightLogs',
            'currentWeight',
            'targetWeight',
            'diffWeight',
            'isSearching',
            'searchSummary'
        ));
    }

    public function search(WeightLogSearchRequest $request)
    {
        $user = Auth::user();

        $query = $user->weightLogs()->newQuery();
        $query->where('user_id', $user->id);



        // 修正: 開始日が入っていたら、その日以降で検索
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        // 修正: 終了日が入っていたら、その日以前で検索
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


        //検索中か判定
        $isSearching = $request->filled('start_date') || $request->filled('end_date'); // 修正: 検索中か判定

        // 修正: 検索条件の文字列を作る
        $startDate = $request->start_date ?: '指定なし';
        $endDate = $request->end_date ?: '指定なし';
        $searchSummary = $startDate . '〜' . $endDate . ' の検索結果 ' . $weightLogs->total() . '件';



        return view('weight_logs.index', compact(
            'weightLogs',
            'currentWeight',
            'targetWeight',
            'diffWeight',
            'isSearching',
            'searchSummary'
        ));
    }

    public function create()
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

        // 修正: create画面では通常検索状態ではない
        $isSearching = false;
        $searchSummary = null;

        return view('weight_logs.create', compact(
            'weightLogs',
            'currentWeight',
            'targetWeight',
            'diffWeight',
            'isSearching',
            'searchSummary'
        ));
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
