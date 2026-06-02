@extends('layouts.admin')
@section('title', 'Pengaturan')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Pengaturan Toko</h1>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf @method('PUT')

        @foreach($settings as $group => $groupSettings)
            <div class="card mb-4">
                <div class="card-header text-capitalize">
                    {{ $group }}
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($groupSettings as $setting)
                            <div class="col-md-6">
                                <label class="form-label">{{ ucfirst(str_replace('_', ' ', $setting->key)) }}</label>
                                @if($setting->type === 'boolean')
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="{{ $setting->key }}" value="0">
                                        <input type="checkbox" name="{{ $setting->key }}" class="form-check-input" value="1" {{ $setting->value ? 'checked' : '' }}>
                                    </div>
                                @elseif($setting->type === 'text' || strlen($setting->value ?? '') > 100)
                                    <textarea name="{{ $setting->key }}" class="form-control" rows="3">{{ $setting->value }}</textarea>
                                @else
                                    <input type="{{ $setting->type === 'integer' ? 'number' : 'text' }}" name="{{ $setting->key }}" class="form-control" value="{{ $setting->value }}">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary px-5">
                <i class="ti ti-device-floppy me-1"></i>Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection
