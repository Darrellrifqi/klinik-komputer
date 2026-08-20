@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; font-size: 0.82rem; color: var(--text-secondary); width: 100%;">
        {{-- Summary Info --}}
        <div>
            Menampilkan
            @if ($paginator->firstItem())
                <span style="font-weight: 700; color: var(--text-primary);">{{ $paginator->firstItem() }}</span>
                sampai
                <span style="font-weight: 700; color: var(--text-primary);">{{ $paginator->lastItem() }}</span>
            @else
                {{ $paginator->count() }}
            @endif
            dari
            <span style="font-weight: 700; color: var(--text-primary);">{{ $paginator->total() }}</span>
            data
        </div>

        {{-- Pagination Buttons --}}
        <div style="display: flex; gap: 4px; align-items: center;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span style="padding: 6px 12px; border-radius: 6px; border: 1px solid var(--border-light, #e2e8f0); background: var(--bg-alt, #f8fafc); color: var(--text-muted, #94a3b8); cursor: not-allowed; opacity: 0.6; font-weight: 600;">
                    &laquo; Prev
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="padding: 6px 12px; border-radius: 6px; border: 1px solid var(--border-light, #cbd5e1); background: var(--bg-card, #ffffff); color: var(--primary, #059669); text-decoration: none; font-weight: 700; transition: all 0.2s ease;">
                    &laquo; Prev
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span style="padding: 6px 10px; color: var(--text-muted, #94a3b8);">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span style="padding: 6px 12px; border-radius: 6px; background: var(--primary, #059669); color: #ffffff; font-weight: 800;">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" style="padding: 6px 12px; border-radius: 6px; border: 1px solid var(--border-light, #cbd5e1); background: var(--bg-card, #ffffff); color: var(--text-primary, #1e293b); text-decoration: none; font-weight: 600; transition: all 0.2s ease;">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="padding: 6px 12px; border-radius: 6px; border: 1px solid var(--border-light, #cbd5e1); background: var(--bg-card, #ffffff); color: var(--primary, #059669); text-decoration: none; font-weight: 700; transition: all 0.2s ease;">
                    Next &raquo;
                </a>
            @else
                <span style="padding: 6px 12px; border-radius: 6px; border: 1px solid var(--border-light, #e2e8f0); background: var(--bg-alt, #f8fafc); color: var(--text-muted, #94a3b8); cursor: not-allowed; opacity: 0.6; font-weight: 600;">
                    Next &raquo;
                </span>
            @endif
        </div>
    </nav>
@endif
