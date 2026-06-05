<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function export()
    {
        return Excel::download(new UsersExport, 'data-pengguna-' . now()->format('Y-m-d') . '.xlsx');
    }
    public function __construct()
    {
        $this->middleware('permission:manage users');
    }

    public function index(Request $request)
    {
        $users = User::with('roles')
            ->when($request->filled('role'), fn($q) => $q->role($request->role))
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('phone', 'like', '%' . $request->search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function show(User $user)
    {
        $user->load(['orders' => fn($q) => $q->orderByDesc('created_at')->limit(10), 'roles']);
        $roles = \Spatie\Permission\Models\Role::all();
        return view('admin.users.show', compact('user', 'roles'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|exists:roles,name']);

        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Tidak ada akses.');
        }

        $user->syncRoles([$request->role]);

        return back()->with('success', 'Role user diperbarui.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Tidak ada akses.');
        }

        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active',
        ]);
        return back()->with('success', 'Status user diperbarui.');
    }
}
