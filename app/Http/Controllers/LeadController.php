<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\LeadAssignedNotification;
use App\Notifications\LeadStatusChangedNotification;

class LeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $leads = $user->getAccessibleLeads()->latest()->paginate(10);
        
        // Get statistics for dashboard
        $stats = [
            'total' => $user->getAccessibleLeads()->count(),
            'new' => $user->getAccessibleLeads()->byStatus('new')->count(),
            'contacted' => $user->getAccessibleLeads()->byStatus('contacted')->count(),
            'qualified' => $user->getAccessibleLeads()->byStatus('qualified')->count(),
        ];

        return view('leads.index', compact('leads', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::whereIn('role', ['sales_manager', 'sales_rep'])->get();
        return view('leads.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:leads,email',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'source' => 'required|string',
            'estimated_value' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id'
        ]);

        $lead = Lead::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
            'title' => $request->title,
            'source' => $request->source,
            'status' => 'new',
            'estimated_value' => $request->estimated_value,
            'notes' => $request->notes,
            'assigned_to' => $request->assigned_to ?? Auth::id(),
            'created_by' => Auth::id(),
        ]);

        if (!empty($lead->assigned_to)) {
                $lead->assignedUser->notify(new LeadAssignedNotification($lead));
        }

        return redirect()->route('leads.index')
            ->with('success', 'Lead created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        // Check authorization
        $this->authorizeLeadAccess($lead);
        
        return view('leads.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead)
    {
        // Check authorization
        $this->authorizeLeadAccess($lead);
        
        $users = User::whereIn('role', ['sales_manager', 'sales_rep'])->get();
        return view('leads.edit', compact('lead', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lead $lead)
    {
        // Check authorization
        $this->authorizeLeadAccess($lead);
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:leads,email,' . $lead->id,
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'source' => 'required|string',
            'status' => 'required|in:new,contacted,qualified,proposal,negotiation,closed_won,closed_lost',
            'estimated_value' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'contacted_at' => 'nullable|date'
        ]);

        $oldStatus = $lead->status;

        $lead->update($request->only([
            'first_name','last_name','email','phone','company','title',
            'source','status','estimated_value','notes','assigned_to','contacted_at'
        ]));

        // Notify assigned user if status changed
        if ($oldStatus !== $lead->status && $lead->assignedUser) {
            $lead->assignedUser->notify(
                new LeadStatusChangedNotification($lead, $oldStatus, $lead->status)
            );
        }

        return redirect()->route('leads.index')
            ->with('success', 'Lead updated successfully.');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        // Check authorization - only admin and creator can delete
        if (!Auth::user()->isAdmin() && $lead->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $lead->delete();

        return redirect()->route('leads.index')
            ->with('success', 'Lead deleted successfully.');
    }

    /**
     * Mark lead as contacted
     */
    public function markContacted(Lead $lead)
    {
        $this->authorizeLeadAccess($lead);
        
        $oldStatus = $lead->status;

        $lead->update([
            'status' => 'contacted',
            'contacted_at' => now()
        ]);

        if ($lead->assignedUser) {
            $lead->assignedUser->notify(
            new LeadStatusChangedNotification($lead, $oldStatus, 'contacted')
            );
        }

        return redirect()->back()->with('success', 'Lead marked as contacted.');

    }

    /**
     * Get leads by status
     */
    public function byStatus($status)
    {
        $user = Auth::user();
        $leads = $user->getAccessibleLeads()->byStatus($status)->latest()->paginate(10);
        
        return view('leads.index', compact('leads', 'status'));
    }

    /**
     * Authorization check for lead access
     */
    private function authorizeLeadAccess(Lead $lead)
    {
        $user = Auth::user();
        
        if ($user->isAdmin() || $user->isSalesManager()) {
            return true;
        }
        
        if ($lead->assigned_to === $user->id || $lead->created_by === $user->id) {
            return true;
        }
        
        abort(403, 'Unauthorized action.');
    }

    public function assign(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
    
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $oldAssignee = $lead->assigned_to;
        $lead->update($validated);

        // Send notification to newly assigned user
        if ($oldAssignee !== $validated['assigned_to']) {
            $assignee = User::find($validated['assigned_to']);
            $assignee->notify(new LeadAssignedNotification($lead));
        }

        return response()->json($lead);
    }
  
}