@extends('layouts.admin')
@section('title', 'Laporan')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Laporan Penjualan</h1>
        <a href="{{ route('admin.reports.export', ['period' => $period]) }}" class="btn btn-outline-primary">
            <i class="ti ti-download me-1"></i>Export Excel
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-2 align-items-center">
                <div class="col-lg-3">
                    <select name="period" class="form-select">
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
            <div class="card p-3 border-start border-4 border-success">
                <p class="text-muted small mb-1">Total Revenue</p>
                <h4 class="fw-bold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card p-3 border-start border-4 border-primary">
                <p class="text-muted small mb-1">Total Pesanan</p>
                <h4 class="fw-bold mb-0">{{ $totalOrders }}</h4>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card p-3 border-start border-4 border-info">
                <p class="text-muted small mb-1">Rata-rata Pesanan</p>
                <h4 class="fw-bold mb-0">
                    Rp {{ number_format($totalOrders > 0 ? $totalRevenue / $totalOrders : 0, 0, ',', '.') }}
                </h4>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card p-3 border-start border-4 border-warning">
                <p class="text-muted small mb-1">Periode</p>
                <h4 class="fw-bold mb-0 text-capitalize">{{ $period }}</h4>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">Metode Pembayaran</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Metode</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paymentMethods as $pm)
                                <tr>
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
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Terjual</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $product)
                                <tr>
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
            <div class="card-body">
                <div style="overflow-x:auto;">
                    <table class="table table-sm">
                        <thead>
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
</div>
@endsection
