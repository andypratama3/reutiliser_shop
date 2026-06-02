<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureCartOwnership
{
    public function handle(Request $request, Closure $next)
    {
        $item = $request->route('item');

        if ($item && $item->cart->user_id !== auth()->id()) {
            abort(403);
        }

        return $next($request);
    }
}
