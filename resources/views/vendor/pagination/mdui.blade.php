@if ($paginator->hasPages())
    <div class="mdui-btn-group">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
			<a class="mdui-btn" href="javascript:;"disabled>
				<i class="mdui-icon material-icons">keyboard_arrow_left</i>
            </a>
        @else
            <a class="mdui-btn" href="{{ $paginator->previousPageUrl() }}">
				<i class="mdui-icon material-icons">keyboard_arrow_left</i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
				<a class="mdui-btn" href="javascript:;"disabled>
					{{$element}}
	            </a>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
            			<a class="mdui-color-theme-accent mdui-btn" href="{{ $url }}">
							{{$page}}
			            </a>
                    @else

            		<a class="mdui-btn" href="{{ $url }}">
						{{$page}}
		            </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="mdui-btn" href="{{ $paginator->nextPageUrl() }}">
				<i class="mdui-icon material-icons">keyboard_arrow_right</i>
            </a>
        @else

			<a class="mdui-btn" href="javascript:;"disabled>
				<i class="mdui-icon material-icons">keyboard_arrow_right</i>
            </a>
        @endif
    </div>
@endif
