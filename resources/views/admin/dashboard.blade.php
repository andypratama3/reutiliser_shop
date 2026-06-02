@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Dashboard</h1>
        <span class="text-muted small">{{ now()->format('d F Y') }}</span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-6">
            <div class="card stat-card p-3" style="background: #e8f5e9;">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-shape bg-white text-success rounded-2">
                        <i class="ti ti-currency-dollar fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Pendapatan Hari Ini</p>
                        <h4 class="fw-bold mb-0">Rp {{ number_format($stats['revenue_today'], 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card stat-card p-3" style="background: #e3f2fd;">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-shape bg-white text-primary rounded-2">
                        <i class="ti ti-package fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Order Hari Ini</p>
                        <h4 class="fw-bold mb-0">{{ $stats['orders_today'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card stat-card p-3" style="background: #fff3e0;">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-shape bg-white text-warning rounded-2">
                        <i class="ti ti-clock fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Menunggu Bayar</p>
                        <h4 class="fw-bold mb-0">{{ $stats['pending_payment'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card stat-card p-3" style="background: #f3e5f5;">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-shape bg-white text-purple rounded-2">
                        <i class="ti ti-building-store fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Total Produk</p>
                        <h4 class="fw-bold mb-0">{{ $stats['total_products'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-6">
            <div class="card p-3 border-start border-4 border-success">
                <p class="text-muted small mb-1">Pendapatan Bulan Ini</p>
                <h4 class="fw-bold mb-0">Rp {{ number_format($stats['revenue_month'], 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card p-3 border-start border-4 border-primary">
                <p class="text-muted small mb-1">Total Pelanggan</p>
                <h4 class="fw-bold mb-0">{{ $stats['total_customers'] }}</h4>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card p-3 border-start border-4 border-warning">
                <p class="text-muted small mb-1">Stok Menipis</p>
                <h4 class="fw-bold mb-0">{{ $stats['low_stock'] }}</h4>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card p-3 border-start border-4 border-info">
                <p class="text-muted small mb-1">Total Pesanan Selesai</p>
                <h4 class="fw-bold mb-0">{{ $stats['orders_today'] }}</h4>
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
                <table class="table table-hover mb-0">
                    <thead>
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
                            <tr>
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
</div>
@endsection
