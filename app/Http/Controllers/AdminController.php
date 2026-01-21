<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Show the staff management / provisioning page.
     */
    public function provision()
    {
        // Fetch all internal staff (exclude clients/guests) ordered by newest first
        $staff = User::whereIn('role', ['admin', 'sales_manager', 'sales_rep'])
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view('admin.provision', compact('staff'));
    }

    /**
     * Store a newly provisioned staff account.
     */
    public function provisionStaff(Request $request)
    {
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

        return redirect()->route('admin.provision')
            ->with('success', "Protocol Alpha: Staff account for {$validated['name']} established.");
    }

    /**
     * Update existing staff details.
     */
    public function updateStaff(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'role'  => 'required|in:admin,sales_manager,sales_rep',
            // Email is optional in the quick-edit toggle
            'email' => ['sometimes', 'email', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($validated);

        // Update password only if the field was filled
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.provision')
            ->with('success', "Registry Updated: {$user->name}'s credentials modified.");
    }

    /**
     * Remove staff from the system.
     */
    public function destroyStaff($id)
    {
        $user = User::findOrFail($id);

        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Self-deletion protocol blocked. System requires an active administrator.');
        }

        // Logic check: Ensure we don't delete the last admin
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Critical Error: Cannot purge the sole remaining Administrator.');
        }

        $user->delete();

        return redirect()->route('admin.provision')
            ->with('success', 'Staff record purged from the archive.');
    }
}