@extends('layouts.admin')
@section('title', 'Pengaturan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fs-3 mb-1">Pengaturan Toko</h1>
        <p class="mb-0 text-muted small">Konfigurasi toko Anda</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf @method('PUT')

    @foreach($settings as $group => $groupSettings)
        <div class="card mb-4">
            <div class="card-header text-capitalize">
                {{ $group }}
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    @foreach($groupSettings as $setting)
                        <div class="col-md-{{ $setting->key === 'shipping_methods' ? '12' : '6' }}">
                            <label class="form-label fw-bold">{{ ucfirst(str_replace('_', ' ', $setting->key)) }}</label>
                            @if($setting->key === 'shipping_methods')
                                <div x-data="{ 
                                    methods: {{ $setting->value ?: '[]' }},
                                    addMethod() {
                                        this.methods.push({ name: '', cost: 0, estimated: '' });
                                    },
                                    removeMethod(index) {
                                        this.methods.splice(index, 1);
                                    }
                                }">
                                    <div class="space-y-3">
                                        <template x-for="(method, index) in methods" :key="index">
                                            <div class="p-3 bg-light rounded-3 mb-3 border">
                                                <div class="row g-2">
                                                    <div class="col-md-5">
                                                        <label class="small text-muted mb-1">Method Name</label>
                                                        <input type="text" x-model="method.name" class="form-control form-control-sm" placeholder="Standard">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="small text-muted mb-1">Cost (IDR)</label>
                                                        <input type="number" x-model="method.cost" class="form-control form-control-sm" placeholder="15000">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="small text-muted mb-1">Estimated</label>
                                                        <input type="text" x-model="method.estimated" class="form-control form-control-sm" placeholder="2-3 days">
                                                    </div>
                                                    <div class="col-md-1 d-flex align-items-end">
                                                        <button type="button" @click="removeMethod(index)" class="btn btn-outline-danger btn-sm border-0">
                                                            <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                    <button type="button" @click="addMethod()" class="btn btn-outline-primary btn-sm mt-2">
                                        <i data-lucide="plus" class="me-1" style="width: 14px; height: 14px;"></i> Add Shipping Method
                                    </button>
                                    <input type="hidden" name="shipping_methods" :value="JSON.stringify(methods)">
                                </div>
                            @elseif($setting->type === 'boolean')
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

    <div class="d-flex justify-content-end mb-4">
        <button type="submit" class="btn btn-primary px-5">
            <i data-lucide="save" class="me-1"></i>Simpan Pengaturan
        </button>
    </div>
</form>
@endsection
