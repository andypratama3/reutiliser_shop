@extends('layouts.app')

@section('title', 'Profil Saya | RÉUTILISER')

@section('content')
<div class="max-w-2xl mx-auto px-margin-mobile md:px-margin-desktop py-8">
    <h1 class="font-headline-md text-headline-md text-primary mb-8">Profil Saya</h1>

    <div class="bg-surface border border-outline-variant p-8">
        <form method="POST" action="{{ route('account.profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="font-label-caps text-label-caps text-on-surface-variant block mb-2">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full bg-transparent border border-outline-variant px-4 py-3 font-body-md text-body-md text-on-background focus:border-primary focus:ring-1 focus:ring-primary outline-none @error('name') border-error @enderror">
                @error('name')
                    <p class="text-error text-sm mt-1 font-body-md">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="font-label-caps text-label-caps text-on-surface-variant block mb-2">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full bg-transparent border border-outline-variant px-4 py-3 font-body-md text-body-md text-on-background focus:border-primary focus:ring-1 focus:ring-primary outline-none @error('email') border-error @enderror">
                @error('email')
                    <p class="text-error text-sm mt-1 font-body-md">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="font-label-caps text-label-caps text-on-surface-variant block mb-2">Phone</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                       class="w-full bg-transparent border border-outline-variant px-4 py-3 font-body-md text-body-md text-on-background focus:border-primary focus:ring-1 focus:ring-primary outline-none">
            </div>

            <hr class="border-outline-variant">

            <p class="font-label-caps text-label-caps text-primary font-bold">Ubah Password (kosongkan jika tidak ingin mengubah)</p>

            <div>
                <label for="current_password" class="font-label-caps text-label-caps text-on-surface-variant block mb-2">Password Saat Ini</label>
                <input id="current_password" type="password" name="current_password"
                       class="w-full bg-transparent border border-outline-variant px-4 py-3 font-body-md text-body-md text-on-background focus:border-primary focus:ring-1 focus:ring-primary outline-none @error('current_password') border-error @enderror">
                @error('current_password')
                    <p class="text-error text-sm mt-1 font-body-md">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="font-label-caps text-label-caps text-on-surface-variant block mb-2">Password Baru</label>
                <input id="password" type="password" name="password"
                       class="w-full bg-transparent border border-outline-variant px-4 py-3 font-body-md text-body-md text-on-background focus:border-primary focus:ring-1 focus:ring-primary outline-none @error('password') border-error @enderror">
                @error('password')
                    <p class="text-error text-sm mt-1 font-body-md">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="font-label-caps text-label-caps text-on-surface-variant block mb-2">Konfirmasi Password Baru</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       class="w-full bg-transparent border border-outline-variant px-4 py-3 font-body-md text-body-md text-on-background focus:border-primary focus:ring-1 focus:ring-primary outline-none">
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-primary text-on-primary px-8 py-3 font-label-caps text-label-caps hover:opacity-90 transition-opacity">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
