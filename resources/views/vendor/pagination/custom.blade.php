<ul class="pagination" data-count-page="{{ $paginator->lastPage() }}">
    @if(!$paginator->onFirstPage())
        <li class="false" data-page="{{ $paginator->currentPage() - 1 }}">
            <a class="prev-page" href="{{ $paginator->previousPageUrl() }}" data-page="{{ $paginator->currentPage() - 1 }}">
                <i class="icon-arrow-left"></i>
            </a>
        </li>
    @endif
    {{-- @if ($paginator->currentPage() > 3)
        <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
    @endif --}}
    @foreach (range(1, $paginator->lastPage()) as $page)
        @if ($page >= $paginator->currentPage() - 2 && $page <= $paginator->currentPage() + 2)
            <li class="{{$page == $paginator->currentPage() ? 'active' : 'false'}}" data-page="{{ $page }}">
                <a href="{{ $paginator->url($page) }}" data-page="{{ $page }}">{{ $page }}</a>
            </li>
        @endif
    @endforeach
    {{-- @if ($paginator->currentPage() < $paginator->lastPage() - 2)
        <a href="{{ $paginator->url($paginator->lastPage()) }}" class="cursor-pointer flex items-center justify-center font-medium border text-sm w-10 h-10 rounded-2xl text-lightgray border-border-gray">{{ $paginator->lastPage() }}</a>
    @endif --}}
    @if ($paginator->hasMorePages())
        <li class="false" data-page="{{ $paginator->currentPage() + 1 }}">
            <a class="next-page" href="{{ $paginator->nextPageUrl() }}" data-page="{{ $paginator->currentPage() + 1 }}"><i class="icon-arrow-right"></i></a>
        </li>
    @endif
</ul>
