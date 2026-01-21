<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index()
    {
        $user = Auth::user();
        
        // If staff, show all. If client, show only theirs.
        if (in_array($user->role, ['admin', 'sales_manager', 'sales_rep'])) {
            $orders = Order::with(['user', 'product'])->latest()->paginate(15);
        } else {
            $orders = Order::with('product')->where('user_id', $user->id)->latest()->get();
        }

        return view('orders.index', compact('orders'));
    }

    /**
     * Update the specified order (Status Update).
     */
    public function update(Request $request, Order $order)
    {
        // Security: Only internal staff can update status
        if (!in_array(Auth::user()->role, ['admin', 'sales_manager', 'sales_rep'])) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update($validated);

        return back()->with('success', 'Order status updated to ' . strtoupper($validated['status']));
    }

    /**
     * Initialize a new order (Client Side).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'total' => 'required|numeric'
        ]);

        Order::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'],
            'total' => $validated['total'],
            'status' => 'pending'
        ]);

        return back()->with('success', 'Acquisition request initialized successfully.');
    }
}