<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApprenantController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'apprenant']);
    }

    /**
     * Show the apprenant dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        return view('apprenant.dashboard');
    }
}
