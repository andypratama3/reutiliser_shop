@extends('layouts.admin')
@section('title', 'Laporan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fs-3 mb-1">Laporan Penjualan</h1>
        <p class="mb-0 text-muted small">Analisis penjualan toko Anda</p>
    </div>
    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exportModal">
        <i class="ti ti-download me-1"></i>Export Excel
    </button>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-2 align-items-center">
            <div class="col-lg-3">
                <select name="period" class="form-control">
                    <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Tahun Ini</option>
                </select>
            </div>
            <div class="col-lg-3">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-3 col-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <h6 class="mb-4">Total Revenue</h6>
                <h3 class="mb-1 fw-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                <p class="mb-0 text-success small">Periode {{ $period }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <h6 class="mb-4">Total Pesanan</h6>
                <h3 class="mb-1 fw-bold">{{ $totalOrders }}</h3>
                <p class="mb-0 text-primary small">Periode {{ $period }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <h6 class="mb-4">Rata-rata Pesanan</h6>
                <h3 class="mb-1 fw-bold">
                    Rp {{ number_format($totalOrders > 0 ? $totalRevenue / $totalOrders : 0, 0, ',', '.') }}
                </h3>
                <p class="mb-0 text-info small">Per transaksi</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <h6 class="mb-4">Periode</h6>
                <h3 class="mb-1 fw-bold text-capitalize">{{ $period }}</h3>
                <p class="mb-0 text-warning small">Rentang laporan</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">Metode Pembayaran</div>
            <div class="card-body p-0">
                <table class="table mb-0 text-nowrap">
                    <thead class="table-light border-light">
                        <tr>
                            <th>Metode</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paymentMethods as $pm)
                            <tr class="align-middle">
                                <td class="text-capitalize">{{ str_replace('_', ' ', $pm->payment_method) }}</td>
                                <td>{{ $pm->count }}</td>
                                <td class="fw-medium">Rp {{ number_format($pm->total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center py-3 text-muted">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">Produk Terlaris</div>
            <div class="card-body p-0">
                <table class="table mb-0 text-nowrap">
                    <thead class="table-light border-light">
                        <tr>
                            <th>Produk</th>
                            <th>Terjual</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $product)
                            <tr class="align-middle">
                                <td class="fw-medium">{{ $product->name }}</td>
                                <td>{{ $product->total_sold ?? 0 }}</td>
                                <td>
                                    @if($product->stock <= 0)
                                        <span class="badge bg-danger">Habis</span>
                                    @elseif($product->stock <= $product->low_stock_threshold)
                                        <span class="badge bg-warning text-dark">{{ $product->stock }}</span>
                                    @else
                                        {{ $product->stock }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center py-3 text-muted">Belum ada data penjualan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if($dailySales->count() > 0)
    <div class="card mt-4">
        <div class="card-header">Penjualan 30 Hari Terakhir</div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-sm text-nowrap">
                    <thead class="table-light border-light">
                        <tr>
                            @foreach($dailySales as $sale)
                                <th class="text-center small">{{ $sale->date->format('d/m') }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach($dailySales as $sale)
                                <td class="text-center fw-medium">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="GET" action="{{ route('admin.reports.export') }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Export Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="date_from" class="form-control" value="{{ old('date_from', request('date_from')) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="date_to" class="form-control" value="{{ old('date_to', request('date_to')) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Metode Pembayaran</label>
                            <select name="payment_method" class="form-control">
                                <option value="">Semua</option>
                                <option value="midtrans" {{ old('payment_method', request('payment_method')) == 'midtrans' ? 'selected' : '' }}>Midtrans</option>
                                <option value="bank_transfer" {{ old('payment_method', request('payment_method')) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cod" {{ old('payment_method', request('payment_method')) == 'cod' ? 'selected' : '' }}>COD</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status Pesanan</label>
                            <select name="status" class="form-control">
                                <option value="">Semua</option>
                                <option value="paid" {{ old('status', request('status')) == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="processing" {{ old('status', request('status')) == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ old('status', request('status')) == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ old('status', request('status')) == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="completed" {{ old('status', request('status')) == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-download me-1"></i>Download Excel
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
