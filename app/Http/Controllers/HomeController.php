<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lead;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get statistics
        $stats = [
            'total' => $user->getAccessibleLeads()->count(),
            'new' => $user->getAccessibleLeads()->where('status', 'new')->count(),
            'contacted' => $user->getAccessibleLeads()->where('status', 'contacted')->count(),
            'qualified' => $user->getAccessibleLeads()->where('status', 'qualified')->count(),
        ];
        
        // Get recent leads
        $recentLeads = $user->getAccessibleLeads()
            ->latest()
            ->take(5)
            ->get();
        
        return view('dashboard', compact('stats', 'recentLeads'));
    }
}