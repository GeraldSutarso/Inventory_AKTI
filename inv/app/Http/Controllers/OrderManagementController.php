<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderManagementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Order::with(['product', 'user', 'kaunit']);

        // 🔍 Filters
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('kaunit_id')) {
            $query->where('kaunit_id', $request->kaunit_id);
        }

        if ($request->filter == 'approved') {
            $query->where('is_approved', true)->where('is_rejected', false);
        } elseif ($request->filter == 'rejected') {
            $query->where('is_rejected', true);
        } elseif ($request->filter == 'acknowledged') {
            $query->where('is_acknowledged', true);
        }

        // 🔐 Role-based filtering
        if ($user->role === 'head') {
            $query->where('kaunit_id', $user->id);
        } elseif ($user->role === 'officer') {
            $query->where('user_id', $user->id);
        }

        $orders = $query->latest()->paginate(10);
        $products = Product::all();
        $kaunits = User::where('role', 'head')->get();

        return view('orders.manage.index', compact('orders', 'products', 'kaunits', 'user'));
    }

    public function approve(Order $order)
    {
        $this->authorizeAction('head');
        $order->update([
            'is_approved' => true,
            'is_rejected' => false,
        ]);
        return back()->with('success', 'Order disetujui.');
    }

    public function reject(Order $order)
    {
        $this->authorizeAction('head');
        $order->update([
            'is_approved' => false,
            'is_rejected' => true,
        ]);
        return back()->with('success', 'Order ditolak.');
    }

    public function acknowledge(Order $order)
    {
        $this->authorizeAction('sarpras');
        $order->update(['is_acknowledged' => true]);
        return back()->with('success', 'Order telah diakui (acknowledged).');
    }

    public function edit(Order $order)
    {
        $user = Auth::user();

        $canEdit = $user->role === 'officer'
            && $order->user_id === $user->id
            && !$order->is_acknowledged
            && (!$order->is_approved || $order->is_rejected);

        if (!$canEdit) {
            abort(403);
        }

        $products = Product::all();
        return view('orders.manage.edit', compact('order', 'products'));
    }

    public function update(Request $request, Order $order)
    {
        $user = Auth::user();

        $canEdit = $user->role === 'officer'
            && $order->user_id === $user->id
            && !$order->is_acknowledged
            && (!$order->is_approved || $order->is_rejected);

        if (!$canEdit) {
            abort(403);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'vendor_tmmin' => 'nullable|string',
            'vendor_akti' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $order->update($validated);
        return redirect()->route('orders.manage.index')->with('success', 'Order diperbarui.');
    }

    private function authorizeAction($allowedRole)
    {
        $user = auth()->user();
        if (!in_array($user->role, [$allowedRole, 'admin'])) {
            abort(403, 'Unauthorized action.');
        }
    }
}
