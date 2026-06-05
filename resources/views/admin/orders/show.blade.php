@extends('layouts.admin')
@section('title', 'Detail Pesanan ' . $order->order_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fs-3 mb-1">Pesanan #{{ $order->order_number }}</h1>
        <p class="mb-0 text-muted small">{{ $order->created_at->format('d M Y H:i') }}</p>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
        <i data-lucide="arrow-left" class="me-1"></i>Kembali
    </a>
</div>

@php
    $badgeMap = [
        'pending' => 'bg-secondary', 'awaiting_payment' => 'bg-warning text-dark',
        'paid' => 'bg-primary', 'processing' => 'bg-info text-dark',
        'shipped' => 'bg-purple', 'delivered' => 'bg-success',
        'completed' => 'bg-success', 'cancelled' => 'bg-danger', 'refunded' => 'bg-danger',
    ];
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Item Pesanan</span>
                <span class="badge {{ $badgeMap[$order->status] ?? 'bg-secondary' }} fs-6">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0 text-nowrap">
                    <thead class="table-light border-light">
                        <tr>
                            <th>Produk</th>
                            <th>Varian</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr class="align-middle">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($item->product_image)
                                            <img src="{{ Storage::url($item->product_image) }}" class="avatar avatar-md rounded">
                                        @endif
                                        <div>
                                            <span class="fw-medium">{{ $item->product_name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $item->variant_info ?? '-' }}</td>
                                <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="fw-medium">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end">Subtotal</td>
                            <td class="fw-medium">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end">Ongkos Kirim</td>
                            <td>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                        </tr>
                        @if($order->discount_amount > 0)
                            <tr>
                                <td colspan="4" class="text-end text-success">Diskon</td>
                                <td class="text-success">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Total</td>
                            <td class="fw-bold fs-5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Informasi Pengiriman</div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Penerima:</strong> {{ $order->recipient_name }}</p>
                        <p class="mb-1"><strong>No. Telp:</strong> {{ $order->recipient_phone }}</p>
                        <p class="mb-1"><strong>Alamat:</strong> {{ $order->shipping_address }}</p>
                        <p class="mb-1"><strong>Kota:</strong> {{ $order->shipping_city }}, {{ $order->shipping_province }}</p>
                        <p class="mb-0"><strong>Kode Pos:</strong> {{ $order->shipping_postal_code }}</p>
                    </div>
                    <div class="col-md-6">
                        @if($order->shipment)
                            <p class="mb-1"><strong>Kurir:</strong> {{ $order->shipment->courier ?? '-' }}</p>
                            <p class="mb-1"><strong>Service:</strong> {{ $order->shipment->service ?? '-' }}</p>
                            <p class="mb-1"><strong>No. Resi:</strong> {{ $order->shipment->tracking_number ?? '-' }}</p>
                            <p class="mb-0"><strong>Status Kirim:</strong>
                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $order->shipment->status)) }}</span>
                            </p>
                        @else
                            <p class="text-muted">Belum ada data pengiriman</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">Customer</div>
            <div class="card-body p-4">
                <p class="mb-1"><strong>{{ $order->user?->name ?? 'Deleted User' }}</strong></p>
                <p class="mb-1 text-muted">{{ $order->user?->email ?? '-' }}</p>
                <p class="mb-0 text-muted">{{ $order->user?->phone ?? '-' }}</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Pembayaran</div>
            <div class="card-body p-4">
                @if($order->payment)
                    <p class="mb-1"><strong>Metode:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment->payment_type ?? $order->payment_method)) }}</p>
                    <p class="mb-1"><strong>Channel:</strong> {{ $order->payment->payment_channel ?? $order->payment_channel }}</p>
                    <p class="mb-1"><strong>Status Bayar:</strong>
                        <span class="badge {{ $order->payment->status == 'settlement' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ ucfirst($order->payment->status) }}
                        </span>
                    </p>
                    @if($order->payment->va_number)
                        <p class="mb-1"><strong>VA:</strong> {{ $order->payment->va_number }}</p>
                    @endif
                    @if($order->paid_at)
                        <p class="mb-0 small text-muted">Lunas: {{ $order->paid_at->format('d M Y H:i') }}</p>
                    @endif
                @else
                    <p class="mb-1"><strong>Metode:</strong> {{ $order->payment_method ?? '-' }}</p>
                    <p class="mb-1"><strong>Channel:</strong> {{ $order->payment_channel ?? '-' }}</p>
                    <p class="mb-0 text-muted">Menunggu pembayaran</p>
                @endif
            </div>
        </div>

        @if($order->promoCode)
            <div class="card mb-4">
                <div class="card-header">Promo</div>
                <div class="card-body p-4">
                    <p class="mb-0"><strong>{{ $order->promoCode->code }}</strong> - {{ $order->promoCode->name }}</p>
                    <p class="mb-0 text-muted small">Diskon: Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</p>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header">Update Status</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="statusOrder" class="form-control" required>
                            <option value="">Pilih Status</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'disabled' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'disabled' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'disabled' : '' }}>Delivered</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'disabled' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'disabled' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div id="shippingFields" class="d-none">
                        <div class="mb-3">
                            <label class="form-label">Kurir <span class="text-danger">*</span></label>
                            <input type="text" name="courier" id="fieldCourier" class="form-control" placeholder="JNE, J&T, SiCepat">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Resi <span class="text-danger">*</span></label>
                            <input type="text" name="tracking_number" id="fieldTracking" class="form-control" placeholder="Masukkan nomor resi">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i data-lucide="refresh-cw" class="me-1"></i>Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('statusOrder')?.addEventListener('change', function () {
        var shippingFields = document.getElementById('shippingFields');
        var isShipped = this.value === 'shipped';
        shippingFields.classList.toggle('d-none', !isShipped);
        document.getElementById('fieldCourier').required = isShipped;
        document.getElementById('fieldTracking').required = isShipped;
    });

    if (document.getElementById('statusOrder')?.value === 'shipped') {
        document.getElementById('shippingFields')?.classList.remove('d-none');
        document.getElementById('fieldCourier').required = true;
        document.getElementById('fieldTracking').required = true;
    }
</script>
@endpush
