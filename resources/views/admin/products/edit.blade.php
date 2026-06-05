@extends('layouts.admin')
@section('title', 'Edit Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fs-3 mb-1">Edit Produk</h1>
        <p class="mb-0 text-muted small">Perbarui informasi produk</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
        <i data-lucide="arrow-left" class="me-1"></i>Kembali
    </a>
</div>

<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="productName" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" id="productSlug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $product->slug) }}">
                        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">SKU <span class="text-danger">*</span></label>
                            <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku) }}" required>
                            @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Harga <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" step="1" required>
                            </div>
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Harga Compare</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="compare_price" class="form-control @error('compare_price') is-invalid @enderror" value="{{ old('compare_price', $product->compare_price) }}" step="1">
                            </div>
                            @error('compare_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Harga Modal</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="cost_price" class="form-control @error('cost_price') is-invalid @enderror" value="{{ old('cost_price', $product->cost_price) }}" step="1">
                            </div>
                            @error('cost_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" min="0" required>
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Batas Stok Rendah</label>
                            <input type="number" name="low_stock_threshold" class="form-control @error('low_stock_threshold') is-invalid @enderror" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Berat (gram)</label>
                            <input type="number" name="weight" class="form-control @error('weight') is-invalid @enderror" value="{{ old('weight', $product->weight) }}" step="0.01">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description', $product->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi Singkat</label>
                        <textarea name="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="2" maxlength="500">{{ old('short_description', $product->short_description) }}</textarea>
                        @error('short_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $product->status) == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ old('status', $product->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" class="form-check-input" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label">Featured Product</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_limited_edition" value="0">
                            <input type="checkbox" name="is_limited_edition" class="form-check-input" value="1" {{ old('is_limited_edition', $product->is_limited_edition) ? 'checked' : '' }}>
                            <label class="form-check-label">Limited Edition</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body p-4">
                    <label class="form-label">Gambar Produk</label>

                    @if($product->images->count() > 0)
                        <div class="row g-2 mb-3" id="existingImages">
                            @foreach($product->images as $img)
                                @php
                                    $url = str_starts_with($img->path, 'http') ? $img->path : Storage::url($img->path);
                                @endphp
                                <div class="col-4" id="img-{{ $img->id }}">
                                    <div class="position-relative">
                                        <img src="{{ $url }}" class="avatar avatar-xl rounded w-100" style="height:100px;object-fit:cover;">
                                        @if($img->is_primary)
                                            <div class="position-absolute top-0 start-0 m-1">
                                                <span class="badge bg-primary">Primary</span>
                                            </div>
                                        @else
                                            <div class="position-absolute top-0 end-0 m-1 d-flex gap-1">
                                                <button type="button" class="btn btn-sm btn-primary rounded-circle"
                                                    style="width:24px;height:24px;padding:0;font-size:12px;line-height:24px;"
                                                    onclick="setPrimary({{ $img->id }})"
                                                    title="Jadikan utama">
                                                    <i data-lucide="star" style="width:12px;height:12px;"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger rounded-circle"
                                                    style="width:24px;height:24px;padding:0;font-size:12px;line-height:24px;"
                                                    onclick="deleteImage({{ $img->id }})"
                                                    title="Hapus gambar">
                                                    &times;
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <input type="file" name="images[]" class="form-control @error('images.*') is-invalid @enderror" multiple accept="image/*" onchange="previewImages(this, 'productPreview')">
                    <small class="text-muted">Upload untuk menambah gambar baru.</small>
                    <div class="row g-2 mt-2" id="productPreview"></div>
                    @error('images.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body p-4">
                    <label class="form-label">Tags</label>
                    <select name="tags[]" class="form-control" multiple data-placeholder="Cari dan pilih tags...">
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $product->tags->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $tag->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-4">
                <i data-lucide="save" class="me-1"></i>Update Produk
            </button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.getElementById('productName')?.addEventListener('keyup', function () {
        const slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
        const slugInput = document.getElementById('productSlug');
        if (!slugInput.dataset.modified) {
            slugInput.value = slug;
        }
    });
    document.getElementById('productSlug')?.addEventListener('input', function () {
        this.dataset.modified = '1';
    });

    function deleteImage(imageId) {
        Swal.fire({
            title: 'Hapus gambar?',
            text: 'Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then(function (result) {
            if (result.isConfirmed) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.products.images.destroy", ":id") }}'.replace(':id', imageId);
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function setPrimary(imageId) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.products.images.set-primary", ":id") }}'.replace(':id', imageId);
        form.innerHTML = '@csrf @method("PATCH")';
        document.body.appendChild(form);
        form.submit();
    }
</script>
@endpush
