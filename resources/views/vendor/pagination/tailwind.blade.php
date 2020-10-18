@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center py-8">
        @unless($paginator->onFirstPage())
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="h-10 w-auto font-semibold text-gray-800 hover:text-gray-900 text-sm flex items-center justify-center mr-3">
                <i class="fas fa-arrow-left mr-2" aria-hidden="true"></i> Previous
            </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span aria-disabled="true">
                    <span class="h-10 w-10 font-semibold text-gray-800 text-sm flex items-center justify-center">{{ $element }}</span>
                </span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="h-10 w-10 bg-blue-800 font-semibold text-white text-sm flex items-center justify-center" aria-current="page">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="h-10 w-10 font-semibold text-gray-800 hover:bg-blue-600 hover:text-white text-sm flex items-center justify-center" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="h-10 w-auto font-semibold text-gray-800 hover:text-gray-900 text-sm flex items-center justify-center ml-3">
                Next <i class="fas fa-arrow-right ml-2" aria-hidden="true"></i>
            </a>
        @endif
    </nav>
@endif
