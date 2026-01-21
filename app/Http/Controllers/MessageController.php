<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $chatPartner = null;
        $contacts = collect();

        // 1. DETERMINE CONTACT LIST BASED ON ROLE
        if ($user->role === 'sales_rep') {
            // Reps see leads assigned to them...
            $assignedEmails = Lead::where('assigned_to', $user->id)->pluck('email');
            $clientContacts = User::whereIn('email', $assignedEmails)->get();
            
            // ...AND Admins/Managers so they can coordinate
            $staffContacts = User::whereIn('role', ['admin', 'sales_manager'])->get();
            
            $contacts = $clientContacts->merge($staffContacts);

        } elseif ($user->role === 'sales_manager' || $user->role === 'admin') {
            // Managers see everyone (Clients, Guests, and Sales Reps)
            $contacts = User::whereIn('role', ['client', 'guest', 'sales_rep'])->get();
        }

        // 2. IDENTIFY ACTIVE CHAT PARTNER
        if ($user->role === 'guest' || $user->role === 'client') {
            $lead = Lead::where('email', $user->email)->first();
            $chatPartner = $lead ? $lead->assignedUser : User::where('role', 'admin')->first();
        } else {
            if ($request->has('client_id')) {
                $chatPartner = User::find($request->client_id);
            } else {
                $chatPartner = $contacts->first();
            }
        }

        // 3. FETCH CONVERSATION THREAD
        $messages = collect();
        if ($chatPartner) {
            $messages = Message::where(function($q) use ($user, $chatPartner) {
                $q->where('sender_id', $user->id)->where('receiver_id', $chatPartner->id);
            })->orWhere(function($q) use ($user, $chatPartner) {
                $q->where('sender_id', $chatPartner->id)->where('receiver_id', $user->id);
            })->oldest()->get();
        }

        return view('terminal.index', compact('messages', 'chatPartner', 'contacts'));
    }

    /**
     * Store a newly created message.
     * Using Manual Save to bypass strict Mass-Assignment issues.
     */
   public function store(Request $request)
{
    // 1. Validate - ensure 'message' is coming from the form
    $request->validate([
        'receiver_id' => 'required',
        'message'     => 'required|string',
    ]);

    // 2. Explicitly create the object
    $msg = new \App\Models\Message();
    $msg->sender_id = auth()->id();
    $msg->receiver_id = $request->receiver_id;
    
    // This line forces the 'message' column to be filled
    $msg->message = $request->message; 
    
    $msg->is_read = false;

    // 3. Save to database
    $msg->save();

    return redirect()->back()->with('success', 'Transmission dispatched.');
}
}