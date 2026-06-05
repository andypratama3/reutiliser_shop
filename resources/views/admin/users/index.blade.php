@extends('layouts.admin')
@section('title', 'Pengguna')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fs-3 mb-1">Pengguna</h1>
        <p class="mb-0 text-muted small">Kelola pengguna toko</p>
    </div>
    <a href="{{ route('admin.users.export') }}" class="btn btn-export-excel">
        <i data-lucide="file-spreadsheet"></i>Export Excel
    </a>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2">
            <div class="col-lg-5">
                <div class="input-group">
                    <span class="input-group-text bg-transparent"><i data-lucide="search" class="size-4"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, email, atau no. telp..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-lg-3">
                <select name="role" class="form-control">
                    <option value="">Semua Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4">
                <button type="submit" class="btn btn-primary me-1">Filter</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="">
    <div class="table-responsive">
        <table class="table mb-0 text-nowrap table-hover">
            <thead class="table-light border-light">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. Telp</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="align-middle">
                        <td class="fw-medium">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm rounded-circle bg-light d-flex align-items-center justify-content-center small fw-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <a href="{{ route('admin.users.show', $user) }}" class="text-decoration-none text-primary">{{ $user->name }}</a>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td class="text-muted">{{ $user->phone ?? '-' }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge bg-primary me-1">{{ ucfirst($role->name) }}</span>
                            @endforeach
                        </td>
                        <td>
                            @if($user->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-light" title="Detail">
                                <i data-lucide="eye" class="size-4"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada pengguna</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna</small>
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
