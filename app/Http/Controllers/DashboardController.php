<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Les middlewares sont gérés dans la route
    }

    /**
     * Redirect users based on their role
     */
    public function index()
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
