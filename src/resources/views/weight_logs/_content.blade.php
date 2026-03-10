<div class="weight-log-page">
    <div class="summary-card">
        <div class="summary-card__item">
            <p class="summary-card__label">目標体重</p>
            <p class="summary-card__value">
                {{ $targetWeight !== null ? number_format($targetWeight, 1) . 'kg' : '未登録' }}
            </p>
        </div>

        <div class="summary-card__item">
            <p class="summary-card__label">目標まで</p>
            <p class="summary-card__value">
                @if ($diffWeight !== null)
                    @if ($diffWeight > 0)
                        -{{ number_format($diffWeight, 1) }}kg
                    @elseif ($diffWeight < 0)
                        +{{ number_format(abs($diffWeight), 1) }}kg
                    @else
                        0.0kg
                    @endif
                @else
                    未登録
                @endif
            </p>
        </div>

        <div class="summary-card__item">
            <p class="summary-card__label">最新体重</p>
            <p class="summary-card__value">
                {{ $currentWeight !== null ? number_format($currentWeight, 1) . 'kg' : '未登録' }}
            </p>
        </div>
    </div>

    <div class="content-card">
        <div class="content-card__top">
            <form action="{{ route('weight_logs.search') }}" method="GET" class="search-form">
                <div class="search-form__group">
                    <input
                        type="date"
                        name="start_date"
                        value="{{ request('start_date') }}"
                        class="search-form__input"
                    >
                    @error('start_date')
                        <p class="search-form__error">{{ $message }}</p>
                    @enderror
                </div>

                <span class="search-form__separator">〜</span>

                <div class="search-form__group">
                    <input
                        type="date"
                        name="end_date"
                        value="{{ request('end_date') }}"
                        class="search-form__input"
                    >
                    @error('end_date')
                        <p class="search-form__error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="search-form__button">検索</button>

                @if ($isSearching)
                    <a href="{{ route('weight_logs.index') }}" class="search-form__reset">リセット</a>
                @endif
            </form>

            <a href="{{ route('weight_logs.create') }}" class="add-button">データ追加</a>
        </div>

        @if ($isSearching)
            <p class="search-result-text">{{ $searchSummary }}</p>
        @endif

        <table class="weight-table">
            <thead>
                <tr>
                    <th>日付</th>
                    <th>体重</th>
                    <th>食事摂取カロリー</th>
                    <th>運動時間</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($weightLogs as $weightLog)
                    <tr>
                        <td>{{ $weightLog->date ? $weightLog->date->format('Y/m/d') : '' }}</td>
                        <td>{{ number_format($weightLog->weight, 1) }}kg</td>
                        <td>{{ $weightLog->calories !== null ? $weightLog->calories . 'cal' : '--' }}</td>
                        <td>{{ $weightLog->exercise_time !== null ? $weightLog->exercise_time : '--' }}</td>
                        <td>
                            <a href="{{ route('weight_logs.edit', $weightLog->id) }}" class="detail-link" aria-label="編集">
                                <span class="detail-link__icon"></span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="weight-table__empty">体重データがありません。</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-area">
            {{ $weightLogs->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>