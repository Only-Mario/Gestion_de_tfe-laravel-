@if ($paginator->hasPages())
    <nav aria-label="Pages">
        <ul class="pages">
            @if (!$paginator->onFirstPage())<li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Page précédente"><i class="fas fa-chevron-left"></i></a></li>@endif
            @foreach ($elements as $element)
                @if (is_string($element))<li><span>{{ $element }}</span></li>@endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>@if ($page == $paginator->currentPage())<span aria-current="page">{{ $page }}</span>@else<a href="{{ $url }}">{{ $page }}</a>@endif</li>
                    @endforeach
                @endif
            @endforeach
            @if ($paginator->hasMorePages())<li><a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Page suivante"><i class="fas fa-chevron-right"></i></a></li>@endif
        </ul>
    </nav>
@endif
