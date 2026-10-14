<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cours; // Remplace par ton modèle approprié

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        $results = Cours::where('name', 'LIKE', "%{$query}%")->get(); // Ajuste selon ta logique
        return response()->json($results);
    }
}
