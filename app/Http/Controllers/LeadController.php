<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Notifications\LeadAssignedNotification;
use App\Notifications\LeadStatusChangedNotification;

class LeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['storePublic']);
    }

    /**
     * Handle public inquiries from the Welcome/Landing Page.
     * Fixed: Changed 'body' to 'message' to prevent Protocol Error.
     */
    public function storePublic(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'company'  => 'nullable|string|max:255',
            'interest' => 'required|string',
            'value'    => 'nullable|numeric',
            'source'   => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            return DB::transaction(function () use ($validated) {
                // 1. Create the User
                $user = User::create([
                    'name'     => $validated['name'],
                    'email'    => $validated['email'],
                    'password' => Hash::make($validated['password']), 
                    'role'     => 'client',
                ]);

                $nameParts = explode(' ', $validated['name'], 2);
                $firstName = $nameParts[0];
                $lastName  = $nameParts[1] ?? 'Inquiry';

                // 2. Create the Lead Record
                Lead::create([
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'email'      => $validated['email'],
                    'phone'      => $validated['phone'],
                    'company'    => $validated['company'],
                    'source'     => $validated['source'] ?? 'Landing Page',
                    'value'      => $validated['value'] ?? 0,
                    'status'     => 'new',
                    'notes'      => "Primary Interest: " . $validated['interest'],
                    'created_by' => $user->id, 
                ]);

                // 3. Auto-send a Welcome Message
                // FIXED: Using 'message' key instead of 'body'
                $admin = User::where('role', 'admin')->first() ?? User::find(1);
                if ($admin) {
                    Message::create([
                        'sender_id'   => $admin->id,
                        'receiver_id' => $user->id,
                        'message'     => "Protocol Initialized. Welcome to your secure dashboard. Your inquiry is being processed.",
                        'is_read'     => false,
                    ]);
                }

                Auth::login($user);
                return redirect()->route('dashboard')->with('success', 'Account established.');
            });
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['email' => 'Protocol Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Display Registry.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->isAdmin() || $user->role === 'sales_manager') {
            $query = Lead::query();
        } else {
            $query = Lead::where('assigned_to', $user->id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('company', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $stats = [
            'total'     => (clone $query)->count(),
            'new'       => (clone $query)->where('status', 'new')->count(),
            'contacted' => (clone $query)->where('status', 'contacted')->count(),
            'qualified' => (clone $query)->where('status', 'qualified')->count(),
        ];

        $leads = $query->latest()->paginate(15);
        
        $users = ($user->isAdmin() || $user->role === 'sales_manager') 
                 ? User::whereIn('role', ['sales_rep', 'sales_manager'])->get() 
                 : collect();

        return view('leads.index', compact('leads', 'stats', 'users'));
    }

    /**
     * Internal lead storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'email'       => 'required|email|unique:leads,email',
            'source'      => 'required|string',
            'value'       => 'nullable|numeric',
            'assigned_to' => 'nullable|exists:users,id'
        ]);

        $lead = Lead::create(array_merge($request->all(), [
            'status'      => 'new',
            'created_by'  => Auth::id(),
            'assigned_to' => $request->assigned_to ?? Auth::id()
        ]));

        if ($lead->assigned_to && $lead->assignedUser) {
            $lead->assignedUser->notify(new LeadAssignedNotification($lead));
        }

        return redirect()->route('leads.index')->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {
        $this->authorizeLeadAccess($lead);
        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $this->authorizeLeadAccess($lead);
        $users = User::whereIn('role', ['sales_manager', 'sales_rep', 'admin'])->get();
        return view('leads.edit', compact('lead', 'users'));
    }

    public function update(Request $request, Lead $lead)
    {
        $this->authorizeLeadAccess($lead);
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'email'      => 'required|email|unique:leads,email,' . $lead->id,
            'status'     => 'required|in:new,contacted,qualified,proposal,negotiation,closed_won,closed_lost',
            'value'      => 'nullable|numeric',
            'source'     => 'nullable|string',
        ]);

        $oldStatus = $lead->status;
        $lead->update($request->all());

        if ($oldStatus !== $lead->status && $lead->assignedUser) {
            $lead->assignedUser->notify(new LeadStatusChangedNotification($lead, $oldStatus, $lead->status));
        }

        return redirect()->route('leads.index')->with('success', 'Lead updated successfully.');
    }
    
    public function destroy(Lead $lead)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only Admins can delete records.');
        }
        
        $lead->delete();
        return redirect()->route('leads.index')->with('success', 'Lead purged.');
    }

    public function markContacted(Lead $lead)
    {
        $this->authorizeLeadAccess($lead);

        $lead->update([
            'status'       => 'contacted',
            'contacted_at' => now()
        ]);

        return redirect()->back()->with('success', 'Contact protocol logged.');
    }

    public function assign(Request $request, $id)
    {
        if (!Auth::user()->isAdmin() && Auth::user()->role !== 'sales_manager') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $lead = Lead::findOrFail($id);
        $lead->update(['assigned_to' => $request->assigned_to]);

        if ($lead->assigned_to && $lead->assignedUser) {
            $lead->assignedUser->notify(new LeadAssignedNotification($lead));
        }

        return response()->json(['message' => 'Lead reassigned successfully.']);
    }

    private function authorizeLeadAccess(Lead $lead)
    {
        $user = Auth::user();
        if ($user->isAdmin() || $user->role === 'sales_manager') return true;
        if ($lead->assigned_to === $user->id) return true;
        
        abort(403, 'Unauthorized access to lead record.');
    }

    public function addNote(Request $request, Lead $lead)
    {
        $request->validate(['content' => 'required|string']);
        $lead->update([
            'notes' => $lead->notes . "\n---\n" . now() . " (" . Auth::user()->name . "): " . $request->content
        ]);
        return back()->with('success', 'Intelligence logged.');
    }
}
