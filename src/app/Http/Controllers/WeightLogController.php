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
    //体重管理画面
    public function index()
    {
        //ログイン中のユーザー情報
        $user = Auth::user();
        //体重の過去の記録を新しい順、8件ずつ並び替えして表示
        $weightLogs = $user->weightLogs()
            ->latest('date')
            ->paginate(8);

        //最新の記録
        $latestLog = $user->weightLogs()->latest('date')->first();
        //目標体重のデータ
        $target = $user->weightTarget;

        //最新の体重（latestlogの中身と繋いで1行にしても大丈夫。コード少なくしたい場合は1行にまとめる）
        $currentWeight = $latestLog?->weight;
        //目標体重
        $targetWeight = $target?->target_weight;
        //体重差（differenceWeight）
        $diffWeight = ($currentWeight !== null && $targetWeight !== null)
            ? round($currentWeight - $targetWeight, 1)
            : null;

        //検索判定用
        $isSearching = false; // 通常一覧では検索中ではない
        $searchSummary = null; //修正: 通常一覧では検索文言なし

        //上記情報を載っけてindexを表示する
        return view('weight_logs.index', compact(
            'weightLogs',
            'currentWeight',
            'targetWeight',
            'diffWeight',
            'isSearching',
            'searchSummary'
        ));
    }

    //検索ボタンを押した後の動き
    public function search(WeightLogSearchRequest $request)
    {
        //ログイン中のユーザー情報
        $user = Auth::user();

        //クエリを作る
        $query = $user->weightLogs()->newQuery();
        //ログイン中のユーザーのidのデータだけ取得
        $query->where('user_id', $user->id);



        //開始日が入っていたら、その日以降で検索
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        //終了日が入っていたら、その日以前で検索
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        //クエリ結果を最新順に並び替えて8件ずつ表示、2ページ目以降へ移動してもクエリ条件を保持しておく
        $weightLogs = $query->latest('date')->paginate(8)->appends($request->query());


        //最新の記録
        $latestLog = $user->weightLogs()->latest('date')->first();
        //目標体重のデータ
        $target = $user->weightTarget;
        //最新の体重
        $currentWeight = $latestLog?->weight;
        //目標体重のデータ
        $targetWeight = $target?->target_weight;
        //体重差（differenceWeight）
        $diffWeight = ($currentWeight !== null && $targetWeight !== null)
            ? round($currentWeight - $targetWeight, 1)
            : null;


        //検索中か判定
        $isSearching = $request->filled('start_date') || $request->filled('end_date');

        //検索条件の文字列を作る
        $startDate = $request->start_date
        ? \Carbon\Carbon::parse($request->start_date)->format('Y年m月d日')
        : '指定なし';
        $endDate = $request->end_date
        ? \Carbon\Carbon::parse($request->end_date)->format('Y年m月d日')
        : '指定なし';
        //検索条件の表示内容
        $searchSummary = $startDate . '〜' . $endDate . ' の検索結果 ' . $weightLogs->total() . '件';

        //上記の条件を載せて、indexを表示する
        return view('weight_logs.index', compact(
            'weightLogs',
            'currentWeight',
            'targetWeight',
            'diffWeight',
            'isSearching',
            'searchSummary'
        ));
    }

    //データ追加ボタン押した後の動き
    public function create()
    {
        //ログイン中のユーザー情報
        $user = Auth::user();
        //体重の過去の記録を新しい順、8件ずつ並び替えして表示
        $weightLogs = $user->weightLogs()
            ->latest('date')
            ->paginate(8);
        //最新の記録
        $latestLog = $user->weightLogs()->latest('date')->first();
        //目標体重のデータ
        $target = $user->weightTarget;
        //最新の体重
        $currentWeight = $latestLog?->weight;
        //目標体重のデータ
        $targetWeight = $target?->target_weight;
        //体重差（differenceWeight）
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

    //データ追加画面の登録ボタンを押した後の動き
    public function store(WeightLogStoreRequest $request)
    {
        //今ログインしているユーザーを取得、体重ログ（user.php利用）にバリデーション済みのデータを使って新規登録する（
        Auth::user()->weightLogs()->create($request->validated());
        //体重管理画面表示
        return redirect()->route('weight_logs.index');
    }

 

    //体重管理画面の鉛筆マークのログの変更のボタンを押した際の動き
    public function edit(WeightLog $weightLog)
    {
        //ログインしているidとログのidが同一か確認
        $this->authorizeLog($weightLog);
        return view('weight_logs.edit', compact('weightLog'));
    }

    //更新した際の動き
    public function update(WeightLogUpdateRequest $request, WeightLog $weightLog)
    {
        //ログインしているidとログのidが同一か確認
        $this->authorizeLog($weightLog);
        $weightLog->update($request->validated());
        return redirect()->route('weight_logs.index');
    }

    //データ削除の動き
    public function destroy(WeightLog $weightLog)
    {
        //ログインしているidとログのidが同一か確認
        $this->authorizeLog($weightLog);
        $weightLog->delete();
        return redirect()->route('weight_logs.index');
    }

    //ログのユーザーidとログインしているidが違ったら、エラー出す
    private function authorizeLog(WeightLog $weightLog): void
    {
        abort_if($weightLog->user_id !== Auth::id(), 403);
    }
}
