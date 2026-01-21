<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Lead;
use App\Models\User;
use App\Models\Message;
use App\Models\Order; // Added this to access Order data

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard based on User Role.
     */
    public function index()
    {
        $user = Auth::user();

        // 1. SHARED: Count unread messages for the logged-in user
        $unreadCount = Message::where('receiver_id', $user->id)
                               ->where('is_read', false)
                               ->count();

        // 2. PORTAL VIEW: GUEST / CLIENT
        // This is for the lead themselves logging in to see their status.
        if ($user->role === 'guest' || $user->role === 'client') {
            $lead = Lead::where('email', $user->email)->first();
            
            // Fetch client's specific orders
            $orders = Order::where('user_id', $user->id)->latest()->get();
            
            return view('portal.dashboard', [
                'lead' => $lead,
                'representative' => $lead ? $lead->assignedUser : null,
                'unreadCount' => $unreadCount,
                'orders' => $orders
            ]);
        }

        // 3. INTERNAL DASHBOARD: STAFF (Admin, Sales Manager, Sales Rep)
        $query = $user->getAccessibleLeads();

        $stats = [
            'total'     => (clone $query)->count(),
            'new'       => (clone $query)->where('status', 'new')->count(),
            'contacted' => (clone $query)->where('status', 'contacted')->count(),
            'qualified' => (clone $query)->where('status', 'qualified')->count(),
            'unread'    => $unreadCount,
        ];

        // Fetch 5 most recent leads for the activity feed
        $recentLeads = (clone $query)->latest()->take(5)->get();

        // NEW: Fetch 5 most recent orders (Acquisitions) for Staff to manage
        // We use 'with' to get User and Product info in one go to prevent crashes
        $recentOrders = Order::with(['user', 'product'])->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recentLeads', 'recentOrders', 'unreadCount'));
    }

    /**
     * Provision new internal staff accounts (Admin Only)
     */
    public function provisionStaff(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:admin,sales_manager,sales_rep',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return back()->with('success', 'Staff account for ' . $validated['name'] . ' has been provisioned.');
    }
}