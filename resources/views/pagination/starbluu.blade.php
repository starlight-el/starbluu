@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $start = max(1, min($current - 1, $last - 2));
        $end = min($last, $start + 2);
    @endphp

    <nav class="d-flex justify-content-between align-items-center flex-wrap gap-2" aria-label="Pagination">
        <p class="small text-muted mb-0">
            Menampilkan {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
        </p>

        <ul class="pagination mb-0">
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                @if ($paginator->onFirstPage())
                    <span class="page-link">&lsaquo;</span>
                @else
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a>
                @endif
            </li>

            @for ($page = $start; $page <= $end; $page++)
                <li class="page-item {{ $page == $current ? 'active' : '' }}">
                    @if ($page == $current)
                        <span class="page-link">{{ $page }}</span>
                    @else
                        <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                    @endif
                </li>
            @endfor

            <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                @if ($paginator->hasMorePages())
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a>
                @else
                    <span class="page-link">&rsaquo;</span>
                @endif
            </li>
        </ul>
    </nav>
@endif