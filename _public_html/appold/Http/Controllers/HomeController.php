<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Redirect users based on their role
     */
    public function dashboard()
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isFormateur()) {
                return redirect()->route('formateur.dashboard');
            } elseif ($user->isApprenant()) {
                return redirect()->route('apprenant.dashboard');
            }
        }

        return redirect('/');
    }
}
