@if ($paginator->hasPages())
    <nav class="gov-pagination-nav" aria-label="Điều hướng trang">
        <ul class="gov-pagination-list">
            @if ($paginator->onFirstPage())
                <li class="gov-pagination-item disabled" aria-disabled="true">
                    <span class="gov-pagination-link"><i class="ph ph-caret-left"></i> Trước</span>
                </li>
            @else
                <li class="gov-pagination-item">
                    <a href="{{ $paginator->previousPageUrl() }}" class="gov-pagination-link" rel="prev">
                        <i class="ph ph-caret-left"></i> Trước
                    </a>
                </li>
            @endif
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="gov-pagination-item disabled" aria-disabled="true">
                        <span class="gov-pagination-link dots">{{ $element }}</span>
                    </li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="gov-pagination-item active" aria-current="page">
                                <span class="gov-pagination-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="gov-pagination-item">
                                <a href="{{ $url }}" class="gov-pagination-link">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach
            @if ($paginator->hasMorePages())
                <li class="gov-pagination-item">
                    <a href="{{ $paginator->nextPageUrl() }}" class="gov-pagination-link" rel="next">
                        Sau <i class="ph ph-caret-right"></i>
                    </a>
                </li>
            @else
                <li class="gov-pagination-item disabled" aria-disabled="true">
                    <span class="gov-pagination-link">Sau <i class="ph ph-caret-right"></i></span>
                </li>
            @endif

        </ul>
    </nav>
@endif