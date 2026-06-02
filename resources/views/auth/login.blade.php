@extends('layouts.app')

@section('title', 'Login | RÉUTILISER')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-margin-mobile md:px-margin-desktop">
    <div class="w-full max-w-md bg-surface border border-outline-variant p-8 md:p-12">
        <h1 class="font-headline-md text-headline-md text-primary text-center mb-2">Welcome Back</h1>
        <p class="font-body-md text-body-md text-on-surface-variant text-center mb-8">Sign in to your account</p>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="font-label-caps text-label-caps text-on-surface-variant block mb-2">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="w-full bg-transparent border border-outline-variant px-4 py-3 font-body-md text-body-md text-on-background focus:border-primary focus:ring-1 focus:ring-primary outline-none @error('email') border-error @enderror"
                       placeholder="your@email.com">
                @error('email')
                    <p class="text-error text-sm mt-1 font-body-md">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="font-label-caps text-label-caps text-on-surface-variant block mb-2">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="w-full bg-transparent border border-outline-variant px-4 py-3 font-body-md text-body-md text-on-background focus:border-primary focus:ring-1 focus:ring-primary outline-none @error('email') border-error @enderror"
                       placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-outline-variant text-primary focus:ring-primary">
                    <span class="font-body-md text-body-md text-on-surface-variant">Remember me</span>
                </label>
            </div>

            <button type="submit"
                    class="w-full bg-primary text-on-primary py-4 font-label-caps text-label-caps hover:opacity-90 transition-opacity">
                Sign In
            </button>
        </form>

        <p class="mt-8 text-center font-body-md text-body-md text-on-surface-variant">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-primary font-semibold hover:underline">Create one</a>
        </p>
    </div>
</div>
@endSection
