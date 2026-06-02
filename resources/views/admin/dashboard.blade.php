@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fs-3 mb-1">Dashboard</h1>
        <p class="mb-0 text-muted small">{{ now()->format('d F Y') }}</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-3 col-6">
        <div class="card p-4 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-2">
            <div class="d-flex gap-3">
                <div class="icon-shape icon-md bg-success text-white rounded-2">
                    <i class="ti ti-currency-dollar fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-3 fs-6">Pendapatan Hari Ini</h2>
                    <h3 class="fw-bold mb-0">Rp {{ number_format($stats['revenue_today'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="card p-4 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-2">
            <div class="d-flex gap-3">
                <div class="icon-shape icon-md bg-primary text-white rounded-2">
                    <i class="ti ti-package fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-3 fs-6">Order Hari Ini</h2>
                    <h3 class="fw-bold mb-0">{{ $stats['orders_today'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="card p-4 bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-2">
            <div class="d-flex gap-3">
                <div class="icon-shape icon-md bg-warning text-white rounded-2">
                    <i class="ti ti-clock fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-3 fs-6">Menunggu Bayar</h2>
                    <h3 class="fw-bold mb-0">{{ $stats['pending_payment'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="card p-4 bg-info bg-opacity-10 border border-info border-opacity-25 rounded-2">
            <div class="d-flex gap-3">
                <div class="icon-shape icon-md bg-info text-white rounded-2">
                    <i class="ti ti-building-store fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-3 fs-6">Total Produk</h2>
                    <h3 class="fw-bold mb-0">{{ $stats['total_products'] }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-3 col-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <h6 class="mb-4">Pendapatan Bulan Ini</h6>
                <h3 class="mb-1 fw-bold">Rp {{ number_format($stats['revenue_month'], 0, ',', '.') }}</h3>
                <p class="mb-0 text-success small">Total revenue bulan ini</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <h6 class="mb-4">Total Pelanggan</h6>
                <h3 class="mb-1 fw-bold">{{ $stats['total_customers'] }}</h3>
                <p class="mb-0 text-primary small">Semua pelanggan terdaftar</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <h6 class="mb-4">Stok Menipis</h6>
                <h3 class="mb-1 fw-bold">{{ $stats['low_stock'] }}</h3>
                <p class="mb-0 text-warning small">Perlu restock segera</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <h6 class="mb-4">Total Pesanan Selesai</h6>
                <h3 class="mb-1 fw-bold">{{ $stats['orders_today'] }}</h3>
                <p class="mb-0 text-info small">Pesanan hari ini</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Order Terbaru</h5>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Lihat semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0 text-nowrap table-hover">
                <thead class="table-light border-light">
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr class="align-middle">
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-primary fw-medium text-decoration-none">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td>{{ $order->user->name }}</td>
                            <td class="fw-medium">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $badgeMap = [
                                        'pending' => 'bg-secondary', 'awaiting_payment' => 'bg-warning text-dark',
                                        'paid' => 'bg-primary', 'processing' => 'bg-info text-dark',
                                        'shipped' => 'bg-purple', 'delivered' => 'bg-success',
                                        'completed' => 'bg-success', 'cancelled' => 'bg-danger', 'refunded' => 'bg-danger',
                                    ];
                                @endphp
                                <span class="badge {{ $badgeMap[$order->status] ?? 'bg-secondary' }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td class="text-muted small">{{ $order->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada pesanan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
