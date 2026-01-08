<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    // Change this line
    protected $redirectTo = '/dashboard'; // Changed from '/home'

    public function __construct()
    {
        $this->middleware('guest');
    }
}