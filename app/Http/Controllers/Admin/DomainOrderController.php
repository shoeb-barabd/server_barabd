<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DomainOrder;
use Illuminate\Http\Request;

class DomainOrderController extends Controller
{
    public function index(Request $request)
    {
        $q      = trim((string) $request->input('q', ''));
        $status = $request->input('status', '');

        $orders = DomainOrder::query()
            ->with('user')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('domain_name', 'like', "%{$q}%")
                      ->orWhere('tran_id', 'like', "%{$q}%")
                      ->orWhereHas('user', fn($u) => $u->where('email', 'like', "%{$q}%"));
                });
            })
            ->when($status !== '', fn($query) => $query->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('back.domain_orders.index', compact('orders', 'q', 'status'));
    }

    public function show(DomainOrder $domain_order)
    {
        $domain_order->load('user');
        return view('back.domain_orders.show', ['order' => $domain_order]);
    }

    public function destroy(DomainOrder $domain_order)
    {
        $domain_order->delete();

        return redirect()->route('admin.domain-orders.index')
            ->with('success', 'Domain order deleted.');
    }
}
