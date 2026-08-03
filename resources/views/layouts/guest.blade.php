<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BusTrack') }} — @yield('title', 'BusTrack')</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-outfit bg-white text-gray-900 antialiased">
        <div class="lg:grid lg:grid-cols-2">
            {{-- ===== Left marketing panel ===== --}}
            <aside class="relative hidden min-h-screen overflow-hidden bg-gray-950 p-12 text-white lg:flex lg:flex-col lg:justify-between">
                <div class="pointer-events-none absolute inset-0">
                    <div class="absolute -top-32 -left-20 size-96 rounded-full bg-brand-500/30 blur-3xl"></div>
                    <div class="absolute bottom-0 right-0 size-96 rounded-full bg-orange-500/20 blur-3xl"></div>
                    <svg class="absolute inset-0 h-full w-full opacity-20" aria-hidden="true">
                        <defs>
                            <pattern id="auth-grid" width="48" height="48" patternUnits="userSpaceOnUse">
                                <path d="M48 0H0V48" fill="none" stroke="#ffffff" stroke-width="1"/>
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#auth-grid)"/>
                    </svg>
                </div>

                <div class="relative flex items-center gap-2.5">
                    <span class="flex size-10 items-center justify-center rounded-xl bg-brand-500 shadow-theme-md">
                        <svg class="size-6 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M6 16.5v-6a6 6 0 0 1 12 0v6a2.25 2.25 0 0 1-2.25 2.25h-1.5v1.5h-1.5v-1.5h-3v1.5h-1.5v-1.5H8.25A2.25 2.25 0 0 1 6 16.5Zm2.25-5.25a.75.75 0 0 0 0 1.5h7.5a.75.75 0 0 0 0-1.5h-7.5Zm0 3a.75.75 0 0 0 0 1.5h7.5a.75.75 0 0 0 0-1.5h-7.5Z"/>
                        </svg>
                    </span>
                    <span class="text-2xl font-semibold tracking-tight">Bus<span class="text-brand-400">Track</span></span>
                </div>

                <div class="relative max-w-md">
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-4 py-1.5 backdrop-blur">
                        <span class="relative flex size-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-success-400 opacity-75"></span>
                            <span class="relative inline-flex size-2 rounded-full bg-success-400"></span>
                        </span>
                        <span class="text-theme-xs font-semibold tracking-wide text-gray-300">Live GPS fleet tracking</span>
                    </div>

                    <h2 class="text-title-sm font-semibold leading-tight tracking-tight">
                        Know where every bus is,<br>
                        <span class="bg-gradient-to-r from-brand-400 to-brand-300 bg-clip-text text-transparent">right now.</span>
                    </h2>

                    <p class="mt-4 text-theme-xl leading-relaxed text-gray-400">
                        Real-time maps, smart ETAs and arrival alerts for parents, drivers and administrators.
                    </p>

                    <ul class="mt-8 space-y-3.5">
                        @foreach (['Live bus tracking on an interactive map', 'Automatic arrival & delay notifications', 'Role-based dashboards for everyone'] as $item)
                            <li class="flex items-start gap-3 text-theme-sm text-gray-300">
                                <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-brand-500/20">
                                    <svg class="size-3 text-brand-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg>
                                </span>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-10 rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur">
                        <div class="flex items-center gap-1 text-warning-400">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="size-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 1.5l2.6 5.3 5.9.9-4.3 4.1 1 5.9L10 15l-5.2 2.7 1-5.9L1.5 7.7l5.9-.9L10 1.5z"/></svg>
                            @endfor
                        </div>
                        <p class="mt-3 text-theme-sm leading-relaxed text-gray-300">
                            "We know the exact minute the bus arrives — no more phone calls on rainy mornings."
                        </p>
                        <p class="mt-3 text-theme-xs font-semibold text-gray-200">— Transport Director, Green Valley Schools</p>
                    </div>
                </div>

                <div class="relative flex items-center gap-8 text-gray-500">
                    @foreach ([['2,400+', 'Buses'], ['98.7%', 'On time'], ['120+', 'Routes']] as [$value, $label])
                        <div>
                            <p class="text-theme-xl font-bold text-white">{{ $value }}</p>
                            <p class="text-theme-xs">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
            </aside>

            {{-- ===== Right form panel ===== --}}
            <div class="relative flex min-h-screen items-center justify-center px-6 py-12 sm:px-8">
                <div class="pointer-events-none absolute inset-0 lg:hidden">
                    <div class="absolute -top-40 left-1/2 h-96 w-96 -translate-x-1/2 rounded-full bg-brand-50 blur-3xl"></div>
                    <div class="absolute -bottom-24 right-0 size-72 rounded-full bg-orange-50 blur-3xl"></div>
                </div>

                <div class="relative w-full max-w-md">
                    <a href="{{ url('/') }}" class="mb-8 flex items-center justify-center gap-2.5 lg:hidden">
                        <span class="flex size-9 items-center justify-center rounded-xl bg-brand-500 shadow-theme-md">
                            <svg class="size-5 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M6 16.5v-6a6 6 0 0 1 12 0v6a2.25 2.25 0 0 1-2.25 2.25h-1.5v1.5h-1.5v-1.5h-3v1.5h-1.5v-1.5H8.25A2.25 2.25 0 0 1 6 16.5Zm2.25-5.25a.75.75 0 0 0 0 1.5h7.5a.75.75 0 0 0 0-1.5h-7.5Zm0 3a.75.75 0 0 0 0 1.5h7.5a.75.75 0 0 0 0-1.5h-7.5Z"/>
                            </svg>
                        </span>
                        <span class="text-xl font-semibold tracking-tight text-gray-900">Bus<span class="text-brand-500">Track</span></span>
                    </a>

                    <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-theme-xl sm:p-10">
                        {{ $slot }}
                    </div>

                    <p class="mt-8 text-center text-theme-xs text-gray-400">
                        © <span id="year"></span> BusTrack · GPS bus tracking software
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
