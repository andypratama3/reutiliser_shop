@extends('layouts.admin')
@section('title', 'Kode Promo')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fs-3 mb-1">Kode Promo</h1>
        <p class="mb-0 text-muted small">Kelola kode promo dan diskon</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPromoModal">
        <i data-lucide="plus" class="me-1"></i>Tambah Promo
    </button>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0 text-nowrap table-hover">
            <thead class="table-light border-light">
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Tipe</th>
                    <th>Nilai</th>
                    <th>Pemakaian</th>
                    <th>Status</th>
                    <th>Berlaku</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promos as $promo)
                    <tr class="align-middle">
                        <td class="fw-bold">{{ $promo->code }}</td>
                        <td>{{ $promo->name }}</td>
                        <td>
                            @php
                                $typeLabels = ['percentage' => 'Persen', 'fixed_amount' => 'Nominal', 'free_shipping' => 'Gratis Ongkir'];
                            @endphp
                            {{ $typeLabels[$promo->type] ?? $promo->type }}
                        </td>
                        <td>
                            @if($promo->type === 'percentage')
                                {{ $promo->value }}%
                            @elseif($promo->type === 'fixed_amount')
                                Rp {{ number_format($promo->value, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $promo->usages_count }}</span>
                            @if($promo->usage_limit)
                                / {{ $promo->usage_limit }}
                            @endif
                        </td>
                        <td>
                            @if($promo->is_active && $promo->isValid())
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="small text-muted">
                            @if($promo->starts_at && $promo->expires_at)
                                {{ $promo->starts_at->format('d/m/Y') }} - {{ $promo->expires_at->format('d/m/Y') }}
                            @elseif($promo->expires_at)
                                Sampai {{ $promo->expires_at->format('d/m/Y') }}
                            @else
                                Tanpa batas
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.promos.destroy', $promo) }}" onsubmit="return confirmDelete(event, 'Hapus promo ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger" title="Hapus">
                                    <i data-lucide="trash-2"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada kode promo</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">Menampilkan {{ $promos->firstItem() ?? 0 }} - {{ $promos->lastItem() ?? 0 }} dari {{ $promos->total() }} promo</small>
            {{ $promos->links() }}
        </div>
    </div>
</div>

<div class="modal fade" id="createPromoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('admin.promos.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kode Promo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kode Promo <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control text-uppercase" value="{{ old('code') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipe <span class="text-danger">*</span></label>
                            <select name="type" class="form-control" required>
                                <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Persentase</option>
                                <option value="fixed_amount" {{ old('type') == 'fixed_amount' ? 'selected' : '' }}>Nominal Tetap</option>
                                <option value="free_shipping" {{ old('type') == 'free_shipping' ? 'selected' : '' }}>Gratis Ongkir</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nilai <span class="text-danger">*</span></label>
                            <input type="number" name="value" class="form-control" value="{{ old('value') }}" step="0.01" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Min. Order</label>
                            <input type="number" name="min_order_amount" class="form-control" value="{{ old('min_order_amount', 0) }}" step="0.01">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Maks. Diskon</label>
                            <input type="number" name="max_discount_amount" class="form-control" value="{{ old('max_discount_amount') }}" step="0.01">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Batas Pemakaian</label>
                            <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit') }}" min="1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Batas Per User</label>
                            <input type="number" name="per_user_limit" class="form-control" value="{{ old('per_user_limit', 1) }}" min="1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" name="description" class="form-control" value="{{ old('description') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mulai</label>
                            <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Berakhir</label>
                            <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label">Aktif</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="hidden" name="is_influencer_code" value="0">
                                <input type="checkbox" name="is_influencer_code" class="form-check-input" value="1" id="isInfluencer" {{ old('is_influencer_code') ? 'checked' : '' }}>
                                <label class="form-check-label" for="isInfluencer">Kode Influencer</label>
                            </div>
                        </div>
                        <div class="col-md-6" id="influencerField" style="{{ old('is_influencer_code') ? '' : 'display:none' }}">
                            <label class="form-label">ID User Influencer</label>
                            <input type="number" name="influencer_user_id" class="form-control" value="{{ old('influencer_user_id') }}" placeholder="User ID influencer">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('isInfluencer')?.addEventListener('change', function () {
        document.getElementById('influencerField').style.display = this.checked ? '' : 'none';
    });
</script>
@endpush
