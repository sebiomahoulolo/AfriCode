<?php

namespace App\Http\Controllers;

use App\Models\Course; // Utilisation du modèle Cours

use Illuminate\Http\Request;


class CoursController extends Controller
{

    public function coursD()
    {   
        return view('pages.coursD');
    }
public function coursT()
{   
    return view('pages.coursT');
}
public function coursE()
{   
    return view('pages.coursE');
}
public function compdisp()
{  
    return view('pages.compdisp');
}

public function test()
{  
    return view('pages.test');
}
public function verifier()
{  
    return view('pages.verifier');
}
public function forumexp()
{  
    return view('pages.forumexp');
}
public function forumapp()
{  
    return view('pages.forumapp');
}

public function contact()
{  
    return view('pages.contact');
}

public function apropos()
{  
    return view('pages.apropos');
}


}
