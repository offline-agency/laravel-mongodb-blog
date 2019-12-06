@if ($paginator->hasPages())
    <div class="pagination" role="navigation">
        {{-- Previous Page Link --}}
        @if (! $paginator->onFirstPage())
            <span class="page-item">
                <a class="btn btn-primary" href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a>
            </span>
       @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <span class="page-item">
                <a class="btn btn-primary" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a>
            </span>
        @else
            <span class="page-item disabled" aria-disabled="true">
                <span class=" btn btn-primary">@lang('pagination.next')</span>
            </span>
        @endif
    </div>
@endif
