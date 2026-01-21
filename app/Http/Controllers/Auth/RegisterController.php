<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Message; // Added this to handle the welcome message
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     */
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data)
    {
        // 1. Create the User
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'client', // Correctly set as client
        ]);

        // 2. Find an Admin to be the "Sender" of the welcome message
        $admin = User::where('role', 'admin')->first();
        $adminId = $admin ? $admin->id : 1; // Fallback to ID 1 if no admin found

        // 3. Create the Welcome Message
        // This solves the "Field 'message' doesn't have a default value" error
        // by ensuring the 'message' key is explicitly filled.
        Message::create([
            'sender_id'   => $adminId,
            'receiver_id' => $user->id,
            'message'     => 'Welcome to the Lead Management Terminal. Your secure correspondence line is now active.',
            'is_read'     => false,
        ]);

        return $user;
    }
}