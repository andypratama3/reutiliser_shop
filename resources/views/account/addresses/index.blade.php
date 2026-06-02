@extends('layouts.app')

@section('title', 'Alamat Saya | RÉUTILISER')

@section('content')
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="font-headline-md text-headline-md text-primary">Alamat Saya</h1>
        <a href="{{ route('account.alamat.create') }}" class="bg-primary text-on-primary px-6 py-3 font-label-caps text-label-caps hover:opacity-90 transition-opacity inline-flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">add</span>
            Tambah Alamat
        </a>
    </div>

    @if($addresses->isEmpty())
        <div class="text-center py-16 border border-dashed border-outline-variant">
            <span class="material-symbols-outlined text-6xl text-outline mb-4">location_off</span>
            <p class="font-body-md text-body-md text-on-surface-variant">Belum ada alamat tersimpan.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($addresses as $address)
                <div class="bg-surface border border-outline-variant p-6 {{ $address->is_default ? 'border-primary' : '' }}">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-label-caps text-label-caps text-primary font-bold">{{ $address->label }}</span>
                                @if($address->is_default)
                                    <span class="bg-primary text-on-primary px-2 py-0.5 font-label-caps text-label-caps text-xs">Utama</span>
                                @endif
                            </div>
                            <p class="font-body-md text-body-md font-semibold mt-1">{{ $address->recipient_name }}</p>
                        </div>
                    </div>
                    <p class="font-body-md text-body-md text-on-surface-variant">{{ $address->phone }}</p>
                    <p class="font-body-md text-body-md text-on-surface-variant mt-1">{{ $address->address }}</p>
                    <p class="font-body-md text-body-md text-on-surface-variant">{{ $address->district ? $address->district . ', ' : '' }}{{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                    <div class="flex gap-3 mt-4 pt-4 border-t border-outline-variant">
                        <a href="{{ route('account.alamat.edit', $address) }}" class="font-label-caps text-label-caps text-primary hover:underline">Edit</a>
                        <form method="POST" action="{{ route('account.alamat.destroy', $address) }}" onsubmit="return confirm('Hapus alamat ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-label-caps text-label-caps text-error hover:underline">Hapus</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
