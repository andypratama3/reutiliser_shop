@extends('layouts.app')

@section('title', 'Register | RÉUTILISER')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-margin-mobile md:px-margin-desktop">
    <div class="w-full max-w-md bg-surface border border-outline-variant p-8 md:p-12">
        <h1 class="font-headline-md text-headline-md text-primary text-center mb-2">Join RÉUTILISER</h1>
        <p class="font-body-md text-body-md text-on-surface-variant text-center mb-8">Create your account</p>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="font-label-caps text-label-caps text-on-surface-variant block mb-2">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                       class="w-full bg-transparent border border-outline-variant px-4 py-3 font-body-md text-body-md text-on-background focus:border-primary focus:ring-1 focus:ring-primary outline-none @error('name') border-error @enderror"
                       placeholder="Your full name">
                @error('name')
                    <p class="text-error text-sm mt-1 font-body-md">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="font-label-caps text-label-caps text-on-surface-variant block mb-2">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                       class="w-full bg-transparent border border-outline-variant px-4 py-3 font-body-md text-body-md text-on-background focus:border-primary focus:ring-1 focus:ring-primary outline-none @error('email') border-error @enderror"
                       placeholder="your@email.com">
                @error('email')
                    <p class="text-error text-sm mt-1 font-body-md">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="font-label-caps text-label-caps text-on-surface-variant block mb-2">Phone (optional)</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" autocomplete="tel"
                       class="w-full bg-transparent border border-outline-variant px-4 py-3 font-body-md text-body-md text-on-background focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                       placeholder="0812xxxxxxxx">
            </div>

            <div>
                <label for="password" class="font-label-caps text-label-caps text-on-surface-variant block mb-2">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       class="w-full bg-transparent border border-outline-variant px-4 py-3 font-body-md text-body-md text-on-background focus:border-primary focus:ring-1 focus:ring-primary outline-none @error('password') border-error @enderror"
                       placeholder="Min. 8 characters">
                @error('password')
                    <p class="text-error text-sm mt-1 font-body-md">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="font-label-caps text-label-caps text-on-surface-variant block mb-2">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                       class="w-full bg-transparent border border-outline-variant px-4 py-3 font-body-md text-body-md text-on-background focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                       placeholder="Repeat your password">
            </div>

            <button type="submit"
                    class="w-full bg-primary text-on-primary py-4 font-label-caps text-label-caps hover:opacity-90 transition-opacity">
                Create Account
            </button>
        </form>

        <p class="mt-8 text-center font-body-md text-body-md text-on-surface-variant">
            Already have an account?
            <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Sign in</a>
        </p>
    </div>
</div>
@endSection
