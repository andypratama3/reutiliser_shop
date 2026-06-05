@extends('layouts.admin')
@section('title', 'Edit Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fs-3 mb-1">Edit Kategori</h1>
        <p class="mb-0 text-muted small">Perbarui informasi kategori</p>
    </div>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
        <i data-lucide="arrow-left" class="me-1"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="catName" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" id="catSlug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $category->slug) }}">
                        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Induk Kategori</label>
                        <select name="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                            <option value="">Tidak ada (induk)</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $category->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Urutan</label>
                            <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $category->sort_order) }}" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gambar</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" onchange="previewSingleImage(this, 'catImagePreview')">
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    @if($category->image)
                        <div class="mb-3">
                            <img id="catImagePreview" src="{{ Storage::url($category->image) }}" class="avatar avatar-xl rounded">
                        </div>
                    @else
                        <img id="catImagePreview" class="mt-2 d-none" style="width:100px;height:100px;object-fit:cover;border-radius:8px;">
                    @endif

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label">Aktif</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save" class="me-1"></i>Update
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('catName')?.addEventListener('keyup', function () {
        const slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
        const slugInput = document.getElementById('catSlug');
        if (!slugInput.dataset.modified) {
            slugInput.value = slug;
        }
    });
    document.getElementById('catSlug')?.addEventListener('input', function () {
        this.dataset.modified = '1';
    });
</script>
@endpush
