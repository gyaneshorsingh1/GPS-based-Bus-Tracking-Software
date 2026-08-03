<x-guest-layout>
    @section('title', 'Log in')

    <x-auth-session-status class="mb-6" :status="session('status')" />

    <div class="mb-8">
        <h1 class="text-title-sm font-bold tracking-tight text-gray-900">Welcome back</h1>
        <p class="mt-2 text-theme-sm leading-relaxed text-gray-500">Log in to track your buses and fleet in real time.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1.5 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@school.edu" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-1.5 w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="size-4 rounded border-gray-300 text-brand-500 shadow-sm focus:ring-brand-500 focus:ring-offset-0" name="remember">
                <span class="ms-2 text-theme-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-theme-sm font-semibold text-brand-600 transition hover:text-brand-700" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <x-primary-button class="w-full">
            {{ __('Log in') }}
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
        </x-primary-button>
    </form>

    @if (Route::has('register'))
        <p class="mt-7 text-center text-theme-sm text-gray-500">
            {{ __('Don\'t have an account?') }}
            <a href="{{ route('register') }}" class="font-semibold text-brand-600 transition hover:text-brand-700">
                {{ __('Create one free') }}
            </a>
        </p>
    @endif
</x-guest-layout>
