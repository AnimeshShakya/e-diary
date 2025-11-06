<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Daily Diary</title>

        <!-- Fonts -->
        <x-guest-layout>
            <div class="text-center space-y-6">
                <h1 class="text-2xl font-semibold text-gray-900">Welcome to {{ config('app.name') }}</h1>

                <p class="text-gray-600">Sign in to continue or create a new account.</p>

                <div class="flex items-center justify-center gap-4">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-500">Log in</a>
                    @endif

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-md bg-gray-800 text-gray-100 hover:bg-gray-700">Register</a>
                    @endif
                </div>

                <div class="pt-4 text-sm text-gray-500">
                    <a href="https://laravel.com/docs" class="underline hover:text-gray-700" target="_blank" rel="noopener">Laravel Docs</a>
                </div>
            </div>
        </x-guest-layout>
                        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
