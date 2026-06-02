@extends('layouts.admin')
@section('title', 'Pesanan')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Pesanan</h1>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-6">
            <div class="card p-3 border-start border-4 border-primary">
                <p class="text-muted small mb-1">Pesanan Hari Ini</p>
                <h4 class="fw-bold mb-0">{{ $stats['total_today'] }}</h4>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card p-3 border-start border-4 border-warning">
                <p class="text-muted small mb-1">Menunggu Pembayaran</p>
                <h4 class="fw-bold mb-0">{{ $stats['pending_payment'] }}</h4>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card p-3 border-start border-4 border-success">
                <p class="text-muted small mb-1">Lunas</p>
                <h4 class="fw-bold mb-0">{{ $stats['total_paid'] }}</h4>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card p-3 border-start border-4 border-info">
                <p class="text-muted small mb-1">Revenue Hari Ini</p>
                <h4 class="fw-bold mb-0">Rp {{ number_format($stats['revenue_today'], 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-2">
                <div class="col-lg-3">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent"><i class="ti ti-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari order number..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-lg-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="awaiting_payment" {{ request('status') == 'awaiting_payment' ? 'selected' : '' }}>Menunggu Bayar</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <input type="date" name="date_from" class="form-control" placeholder="Dari tanggal" value="{{ request('date_from') }}">
                </div>
                <div class="col-lg-2">
                    <input type="date" name="date_to" class="form-control" placeholder="Sampai tanggal" value="{{ request('date_to') }}">
                </div>
                <div class="col-lg-3">
                    <button type="submit" class="btn btn-primary me-1">Filter</button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-primary fw-medium text-decoration-none">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td>
                                    <div>{{ $order->user->name }}</div>
                                    <small class="text-muted">{{ $order->user->email }}</small>
                                </td>
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
                                <td class="text-muted small">{{ $order->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-light" title="Detail">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada pesanan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Menampilkan {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} dari {{ $orders->total() }} pesanan</small>
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
