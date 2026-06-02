<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = auth()->user()->addresses()->orderByDesc('is_default')->orderByDesc('created_at')->get();
        return view('account.addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('account.addresses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'label'          => 'required|string|max:50',
            'recipient_name' => 'required|string|max:100',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string',
            'district'       => 'nullable|string|max:100',
            'city'           => 'required|string|max:100',
            'province'       => 'required|string|max:100',
            'postal_code'    => 'required|string|max:10',
            'is_default'     => 'boolean',
        ]);

        $data = $request->all();

        if ($request->boolean('is_default')) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }

        auth()->user()->addresses()->create($data);

        return redirect()->route('account.alamat.index')->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function edit(Address $alamat)
    {
        if ($alamat->user_id !== auth()->id()) {
            abort(403);
        }

        return view('account.addresses.edit', compact('alamat'));
    }

    public function update(Request $request, Address $alamat)
    {
        if ($alamat->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'label'          => 'required|string|max:50',
            'recipient_name' => 'required|string|max:100',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string',
            'district'       => 'nullable|string|max:100',
            'city'           => 'required|string|max:100',
            'province'       => 'required|string|max:100',
            'postal_code'    => 'required|string|max:10',
            'is_default'     => 'boolean',
        ]);

        $data = $request->all();

        if ($request->boolean('is_default')) {
            auth()->user()->addresses()->where('id', '!=', $alamat->id)->update(['is_default' => false]);
        }

        $alamat->update($data);

        return redirect()->route('account.alamat.index')->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroy(Address $alamat)
    {
        if ($alamat->user_id !== auth()->id()) {
            abort(403);
        }

        $alamat->delete();

        return back()->with('success', 'Alamat berhasil dihapus.');
    }
}
