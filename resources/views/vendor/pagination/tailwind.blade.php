@if ($paginator->hasPages())
<nav class="flex justify-center gap-2 mt-8" aria-label="Pagination">
  @if ($paginator->onFirstPage())
    <span class="px-3 py-2 rounded-lg bg-gray-100 text-gray-400 text-sm cursor-not-allowed">←</span>
  @else
    <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gold hover:border-gold hover:text-navy-deep text-gray-600 text-sm transition-all">←</a>
  @endif

  @foreach ($elements as $element)
    @if (is_string($element))
      <span class="px-3 py-2 text-gray-400 text-sm">{{ $element }}</span>
    @endif
    @if (is_array($element))
      @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
          <span class="px-3 py-2 rounded-lg bg-navy-mid text-white font-bold text-sm">{{ $page }}</span>
        @else
          <a href="{{ $url }}" class="px-3 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gold hover:border-gold hover:text-navy-deep text-gray-600 text-sm transition-all">{{ $page }}</a>
        @endif
      @endforeach
    @endif
  @endforeach

  @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gold hover:border-gold hover:text-navy-deep text-gray-600 text-sm transition-all">→</a>
  @else
    <span class="px-3 py-2 rounded-lg bg-gray-100 text-gray-400 text-sm cursor-not-allowed">→</span>
  @endif
</nav>
@endif
