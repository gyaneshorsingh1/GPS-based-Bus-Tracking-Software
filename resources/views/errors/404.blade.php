<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8" />
        <meta
            name="viewport"
            content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
        />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>{{ __('404 Error Page') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body
        x-data="{ page: 'page404', loaded: true, darkMode: false, sidebarToggle: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem('darkMode')); $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
        :class="{ 'dark bg-gray-900': darkMode === true }"
    >
        @include('partials.preloader')

        <!-- ===== Page Wrapper Start ===== -->
        <div
            class="relative z-1 flex min-h-screen flex-col items-center justify-center overflow-hidden p-6"
        >
            <!-- ===== Common Grid Shape Start ===== -->
            @include('partials.common-grid-shape')
            <!-- ===== Common Grid Shape End ===== -->

            <!-- Centered Content -->
            <div class="mx-auto w-full max-w-[242px] text-center sm:max-w-[472px]">
                <h1
                    class="mb-8 text-title-md font-bold text-gray-800 dark:text-white/90 xl:text-title-2xl"
                >
                    ERROR
                </h1>

                <img src="/images/error/404.svg" alt="404" class="dark:hidden" />
                <img
                    src="/images/error/404-dark.svg"
                    alt="404"
                    class="hidden dark:block"
                />

                <p
                    class="mb-6 mt-10 text-base text-gray-700 dark:text-gray-400 sm:text-lg"
                >
                    {{ __('We can’t seem to find the page you are looking for!') }}
                </p>

                <a
                    href="{{ url('/') }}"
                    class="shadow-theme-xs inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-3.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200"
                >
                    {{ __('Back to Home Page') }}
                </a>
            </div>
            <!-- Footer -->
            <p
                class="absolute bottom-6 left-1/2 -translate-x-1/2 text-center text-sm text-gray-500 dark:text-gray-400"
            >
                &copy; <span id="year"></span> - TailAdmin
            </p>
        </div>
        <!-- ===== Page Wrapper End ===== -->
    </body>
</html>
