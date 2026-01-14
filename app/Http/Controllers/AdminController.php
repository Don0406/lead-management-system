<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        
        \Log::info('Admin dashboard accessed by: ' . auth()->user()->name);
        
        $stats = [
            'total' => Lead::count(),
            'new' => Lead::where('status', 'new')->count(),
            'contacted' => Lead::where('status', 'contacted')->count(),
            'qualified' => Lead::where('status', 'qualified')->count(),
        ];
        
        $recentLeads = Lead::with('assignedUser')->latest()->take(10)->get();
        
        return view('admin.dashboard', compact('stats', 'recentLeads'));
    }
}