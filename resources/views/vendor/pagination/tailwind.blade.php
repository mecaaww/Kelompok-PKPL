@if ($paginator->hasPages())
<nav class="flex items-center gap-1">

    {{-- PREVIOUS --}}
    @if ($paginator->onFirstPage())
        <span class="px-3 py-1 rounded-lg border text-gray-400 cursor-not-allowed">
            Prev
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}"
           class="px-3 py-1 rounded-lg border text-gray-600 hover:bg-gray-100">
            Prev
        </a>
    @endif

    {{-- PAGE NUMBERS --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="px-2">...</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="px-3 py-1 rounded-lg bg-[var(--primary)] text-white">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                       class="px-3 py-1 rounded-lg border hover:bg-gray-100">
                        {{ $page }}
                    </a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- NEXT --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"
           class="px-3 py-1 rounded-lg border text-gray-600 hover:bg-gray-100">
            Next
        </a>
    @else
        <span class="px-3 py-1 rounded-lg border text-gray-400 cursor-not-allowed">
            Next
        </span>
    @endif

</nav>
@endif
