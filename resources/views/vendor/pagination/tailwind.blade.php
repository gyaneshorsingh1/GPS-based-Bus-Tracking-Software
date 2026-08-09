@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex cursor-default items-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium leading-5 text-gray-400 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-500">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition ease-in-out duration-150 hover:bg-gray-50 hover:text-gray-900 focus:outline-none dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-300 dark:hover:bg-white/[0.06] dark:hover:text-white">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative ml-3 inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition ease-in-out duration-150 hover:bg-gray-50 hover:text-gray-900 focus:outline-none dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-300 dark:hover:bg-white/[0.06] dark:hover:text-white">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="relative ml-3 inline-flex cursor-default items-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium leading-5 text-gray-400 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-500">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-theme-sm leading-5 text-gray-500 dark:text-gray-400">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-xl shadow-theme-sm rtl:flex-row-reverse">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex cursor-default items-center rounded-l-xl border border-gray-200 bg-white px-2 py-2 text-sm font-medium leading-5 text-gray-300 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-600" aria-hidden="true">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center rounded-l-xl border border-gray-200 bg-white px-2 py-2 text-sm font-medium leading-5 text-gray-500 transition ease-in-out duration-150 hover:bg-gray-50 hover:text-gray-800 focus:z-10 focus:outline-none active:bg-gray-100 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white dark:active:bg-white/[0.08]" aria-label="{{ __('pagination.previous') }}">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative -ml-px inline-flex cursor-default items-center border border-gray-200 bg-white px-4 py-2 text-sm font-medium leading-5 text-gray-500 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-400">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative -ml-px inline-flex cursor-default items-center border border-brand-500 bg-brand-500 px-4 py-2 text-sm font-semibold leading-5 text-white dark:border-brand-500 dark:bg-brand-500 dark:text-white">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative -ml-px inline-flex items-center border border-gray-200 bg-white px-4 py-2 text-sm font-medium leading-5 text-gray-600 transition ease-in-out duration-150 hover:bg-gray-50 hover:text-gray-900 focus:z-10 focus:outline-none active:bg-gray-100 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white dark:active:bg-white/[0.08]" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative -ml-px inline-flex items-center rounded-r-xl border border-gray-200 bg-white px-2 py-2 text-sm font-medium leading-5 text-gray-500 transition ease-in-out duration-150 hover:bg-gray-50 hover:text-gray-800 focus:z-10 focus:outline-none active:bg-gray-100 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white dark:active:bg-white/[0.08]" aria-label="{{ __('pagination.next') }}">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative -ml-px inline-flex cursor-default items-center rounded-r-xl border border-gray-200 bg-white px-2 py-2 text-sm font-medium leading-5 text-gray-300 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-600" aria-hidden="true">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
