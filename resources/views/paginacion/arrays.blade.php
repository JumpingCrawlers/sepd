@if ($paginator->hasPages())
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $paginator->appends(request()->except('page'))->previousPageUrl() }}" rel="prev">&laquo;</a></li>
        @endif

        @if($paginator->currentPage() > 3)
            <li class="page-item hidden-xs"><a class="page-link" href="{{ $paginator->appends(request()->except('page'))->url(1) }}">1</a></li>
        @endif
        @if($paginator->currentPage() > 4)
            <li class="page-item disabled hidden-xs"><a href="#" class="page-link">...</a></li>
        @endif
        @foreach(range(1, $paginator->lastPage()) as $i)
            @if($i >= $paginator->currentPage() - 2 && $i <= $paginator->currentPage() + 2)
                @if ($i == $paginator->currentPage())
                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                @else
                    <li class="page-item pagination-page-nav"><a class="page-link" href="{{ $paginator->appends(request()->except('page'))->url($i) }}">{{ $i }}</a></li>
                @endif
            @endif
        @endforeach
        @if($paginator->currentPage() < $paginator->lastPage() - 3)
            <li class="page-item pagination-page-nav disabled hidden-xs"><a href="#" class="page-link">...</a></li>
        @endif
        @if($paginator->currentPage() < $paginator->lastPage() - 2)
            <li class="page-item pagination-page-nav hidden-xs"><a class="page-link" href="{{ $paginator->appends(request()->except('page'))->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a></li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->appends(request()->except('page'))->nextPageUrl() }}" rel="next">&raquo;</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
        @endif
    </ul>
@endif