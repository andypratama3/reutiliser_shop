@extends('layouts.admin')
@section('title', 'Detail Pengguna')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fs-3 mb-1">Detail Pengguna</h1>
        <p class="mb-0 text-muted small">Informasi dan pengaturan pengguna</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
        <i data-lucide="arrow-left" class="me-1"></i>Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center p-4">
                <div class="avatar avatar-lg rounded-circle bg-light d-flex align-items-center justify-content-center fs-3 fw-bold mx-auto mb-3">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h5 class="mb-1">{{ $user->name }}</h5>
                <p class="text-muted mb-2">{{ $user->email }}</p>
                <p class="mb-2">{{ $user->phone ?? '-' }}</p>
                <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }} fs-6">
                    {{ ucfirst($user->status) }}
                </span>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">Role Management</div>
            <div class="card-body p-4">
                @foreach($user->roles as $role)
                    <span class="badge bg-primary fs-6">{{ ucfirst($role->name) }}</span>
                @endforeach

                <hr>
                <form method="POST" action="{{ route('admin.users.role', $user) }}">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label">Ubah Role</label>
                        <select name="role" class="form-control" required>
                            <option value="">Pilih Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Role</button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">Akun</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" onsubmit="return confirmDelete(event, '{{ $user->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }} pengguna ini?')">
                    @csrf @method('PUT')
                    @if($user->status === 'active')
                        <button type="submit" class="btn btn-warning w-100">
                            <i data-lucide="pause" class="me-1"></i>Nonaktifkan
                        </button>
                    @else
                        <button type="submit" class="btn btn-success w-100">
                            <i data-lucide="play" class="me-1"></i>Aktifkan
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Pesanan Terbaru</span>
                <span class="badge bg-primary">{{ $user->orders->count() }} pesanan</span>
            </div>
            <div class="card-body p-0">
                @php
                    $badgeMap = [
                        'pending' => 'bg-secondary', 'awaiting_payment' => 'bg-warning text-dark',
                        'paid' => 'bg-primary', 'processing' => 'bg-info text-dark',
                        'shipped' => 'bg-purple', 'delivered' => 'bg-success',
                        'completed' => 'bg-success', 'cancelled' => 'bg-danger', 'refunded' => 'bg-danger',
                    ];
                @endphp
                <table class="table mb-0 text-nowrap table-hover">
                    <thead class="table-light border-light">
                        <tr>
                            <th>Order #</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->orders as $order)
                            <tr class="align-middle">
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-primary text-decoration-none">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="fw-medium">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $badgeMap[$order->status] ?? 'bg-secondary' }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada pesanan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
