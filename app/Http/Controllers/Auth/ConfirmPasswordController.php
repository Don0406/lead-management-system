<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ConfirmsPasswords;

class ConfirmPasswordController extends Controller
{
    use ConfirmsPasswords;

    // Change this line
    protected $redirectTo = '/dashboard'; // Changed from '/home'

    public function __construct()
    {
        $this->middleware('auth');
    }
}