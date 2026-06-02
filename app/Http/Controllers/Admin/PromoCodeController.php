<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage promo codes');
    }

    public function index()
    {
        $promos = PromoCode::withCount('usages')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.promos.index', compact('promos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'                => 'required|string|unique:promo_codes,code|max:50',
            'name'                => 'required|string|max:100',
            'description'         => 'nullable|string|max:500',
            'type'                => 'required|in:percentage,fixed_amount,free_shipping',
            'value'               => 'required|numeric|min:0',
            'min_order_amount'    => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit'         => 'nullable|integer|min:1',
            'per_user_limit'      => 'required|integer|min:1',
            'is_influencer_code'  => 'boolean',
            'influencer_user_id'  => 'nullable|exists:users,id',
            'is_active'           => 'boolean',
            'starts_at'           => 'nullable|date',
            'expires_at'          => 'nullable|date|after:starts_at',
        ]);

        PromoCode::create(array_merge(
            $request->except('_token'),
            ['code' => strtoupper($request->code)]
        ));

        return redirect()->route('admin.promos.index')
            ->with('success', 'Kode promo berhasil dibuat.');
    }

    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();
        return back()->with('success', 'Kode promo dihapus.');
    }
}
