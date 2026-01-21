<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // Fetch only the orders belonging to the logged-in user
        $orders = Order::where('user_id', auth()->id())
            ->with('product') // Assumes you have a 'product' relationship in Order model
            ->latest()
            ->get();

        return view('portal.orders.index', compact('orders'));
    }
}