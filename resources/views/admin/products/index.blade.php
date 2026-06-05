@extends('layouts.admin')
@section('title', 'Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fs-3 mb-1">Produk</h1>
        <p class="mb-0 text-muted small">Kelola produk toko Anda</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.products.export') }}" class="btn btn-export-excel">
            <i data-lucide="file-spreadsheet"></i>Export Excel
        </a>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i data-lucide="plus" class="me-1"></i>Tambah Produk
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('admin.products.index') }}" class="row g-2">
            <div class="col-lg-5">
                <div class="input-group">
                    <span class="input-group-text bg-transparent"><i data-lucide="search" class="size-4"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama atau SKU..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-lg-3">
                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div class="col-lg-2">
                <select name="category_id" class="form-control">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="">
    <div class="table-responsive">
        <table class="table mb-0 text-nowrap table-hover">
            <thead class="table-light border-light">
                <tr>
                    <th style="width:60px">Gambar</th>
                    <th>Nama</th>
                    <th>SKU</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="align-middle">
                        <td>
                            @if($product->primaryImage)
                                @php
                                    $path = $product->primaryImage->path;
                                    $url = str_starts_with($path, 'http') ? $path : Storage::url($path);
                                @endphp
                                <img src="{{ $url }}" alt="{{ $product->name }}" class="avatar avatar-md rounded">
                            @else
                                <div class="avatar avatar-md rounded bg-light d-flex align-items-center justify-content-center small fw-bold">📦</div>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-decoration-none fw-medium text-primary">{{ $product->name }}</a>
                            @if($product->is_featured)<span class="badge bg-warning text-dark ms-1">Featured</span>@endif
                            @if($product->is_limited_edition)<span class="badge bg-info ms-1">Limited</span>@endif
                        </td>
                        <td class="text-muted small">{{ $product->sku }}</td>
                        <td>{{ $product->category?->name ?? '-' }}</td>
                        <td class="fw-medium">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            @if($product->stock <= 0)
                                <span class="badge bg-danger">Habis</span>
                            @elseif($product->stock <= $product->low_stock_threshold)
                                <span class="badge bg-warning text-dark">{{ $product->stock }}</span>
                            @else
                                <span class="text-muted">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusBadge = ['draft' => 'bg-secondary', 'published' => 'bg-success', 'archived' => 'bg-danger'];
                            @endphp
                            <span class="badge {{ $statusBadge[$product->status] ?? 'bg-secondary' }}">{{ ucfirst($product->status) }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-light" title="Edit">
                                    <i data-lucide="edit-3" class="size-4"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirmDelete(event, 'Hapus produk ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light text-danger" title="Hapus">
                                        <i data-lucide="trash-2" class="size-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada produk</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk</small>
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
