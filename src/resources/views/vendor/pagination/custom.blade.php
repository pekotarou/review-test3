@if ($paginator->hasPages())
    <nav class="custom-pagination">
        {{-- 前へ --}}
        @if ($paginator->onFirstPage())
            <span class="custom-pagination__arrow custom-pagination__arrow--disabled">&lt;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="custom-pagination__arrow">&lt;</a>
        @endif

        {{-- ページ番号 --}}
        <div class="custom-pagination__pages">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="custom-pagination__dots">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="custom-pagination__page custom-pagination__page--active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="custom-pagination__page">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- 次へ --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="custom-pagination__arrow">&gt;</a>
        @else
            <span class="custom-pagination__arrow custom-pagination__arrow--disabled">&gt;</span>
        @endif
    </nav>
@endif