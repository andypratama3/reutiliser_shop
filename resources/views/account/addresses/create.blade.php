@extends('layouts.app')

@section('title', 'Tambah Alamat | RÉUTILISER')

@section('content')
<div class="max-w-2xl mx-auto px-margin-mobile md:px-margin-desktop py-8">
    <a href="{{ route('account.alamat.index') }}" class="inline-flex items-center gap-2 font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors mb-6">
        <span class="material-symbols-outlined">arrow_back</span>
        Kembali
    </a>

    <h1 class="font-headline-md text-headline-md text-primary mb-8">Tambah Alamat Baru</h1>

    <div class="bg-surface border border-outline-variant p-8">
        <form method="POST" action="{{ route('account.alamat.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="label" class="font-label-caps text-label-caps text-on-surface-variant block mb-2">Label</label>
                    <input id="label" type="text" name="label" value="{{ old('label', 'Rumah') }}" required
                           class="w-full bg-transparent border border-outline-variant px-4 py-3 font-body-md text-body-md text-on-background focus:border-primary focus:ring-1 focus:ring-primary outline-none @error('label') border-error @enderror">
                    @error('label') <p class="text-error text-sm mt-1 font-body-md">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="recipient_name" class="font-label-caps text-label-caps text-on-surface-variant block mb-2">Nama Penerima</label>
                    <input id="recipient_name" type="text" name="recipient_name" value="{{ old('recipient_name') }}" required
                           class="w-full bg-transparent border border-outline-variant px-4 py-3 font-body-md text-body-md text-on-background focus:border-primary focus:ring-1 focus:ring-primary outline-none @error('recipient_name') border-error @enderror">
                    @error('recipient_name') <p class="text-error text-sm mt-1 font-body-md">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="phone" class="font-label-caps text-label-caps text-on-surface-variant block mb-2">No. Telepon</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required
                           class="w-full bg-transparent border border-outline-variant px-4 py-3 font-body-md text-body-md text-on-background focus:border-primary focus:ring-1 focus:ring-primary outline-none @error('phone') border-error @enderror">
                    @error('phone') <p class="text-error text-sm mt-1 font-body-md">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="address" class="font-label-caps text-label-caps text-on-surface-variant block mb-2">Alamat Lengkap</label>
                <textarea id="address" name="address" rows="3" required
                          class="w-full bg-transparent border border-outline-variant px-4 py-3 font-body-md text-body-md text-on-background focus:border-primary focus:ring-1 focus:ring-primary outline-none @error('address') border-error @enderror">{{ old('address') }}</textarea>
                @error('address') <p class="text-error text-sm mt-1 font-body-md">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="district" class="font-label-caps text-label-caps text-on-surface-variant block mb-2">Kecamatan</label>
                <input id="district" type="text" name="district" value="{{ old('district') }}"
                       class="w-full bg-transparent border border-outline-variant px-4 py-3 font-body-md text-body-md text-on-background focus:border-primary focus:ring-1 focus:ring-primary outline-none">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="city" class="font-label-caps text-label-caps text-on-surface-variant block mb-2">Kota</label>
                    <input id="city" type="text" name="city" value="{{ old('city') }}" required
                           class="w-full bg-transparent border border-outline-variant px-4 py-3 font-body-md text-body-md text-on-background focus:border-primary focus:ring-1 focus:ring-primary outline-none @error('city') border-error @enderror">
                    @error('city') <p class="text-error text-sm mt-1 font-body-md">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="province" class="font-label-caps text-label-caps text-on-surface-variant block mb-2">Provinsi</label>
                    <input id="province" type="text" name="province" value="{{ old('province') }}" required
                           class="w-full bg-transparent border border-outline-variant px-4 py-3 font-body-md text-body-md text-on-background focus:border-primary focus:ring-1 focus:ring-primary outline-none @error('province') border-error @enderror">
                    @error('province') <p class="text-error text-sm mt-1 font-body-md">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="postal_code" class="font-label-caps text-label-caps text-on-surface-variant block mb-2">Kode Pos</label>
                    <input id="postal_code" type="text" name="postal_code" value="{{ old('postal_code') }}" required
                           class="w-full bg-transparent border border-outline-variant px-4 py-3 font-body-md text-body-md text-on-background focus:border-primary focus:ring-1 focus:ring-primary outline-none @error('postal_code') border-error @enderror">
                    @error('postal_code') <p class="text-error text-sm mt-1 font-body-md">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_default" id="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}
                       class="rounded border-outline-variant text-primary focus:ring-primary">
                <label for="is_default" class="font-body-md text-body-md text-on-surface-variant cursor-pointer">Jadikan alamat utama</label>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-primary text-on-primary px-8 py-3 font-label-caps text-label-caps hover:opacity-90 transition-opacity">
                    Simpan Alamat
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
