<?php
namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionTestCase;
use Illuminate\Http\Request;

class AdminCompetitionTestCaseController extends Controller
{
    public function index(Competition $competition)
    {
        $testcases = $competition->testCases()->latest()->get();
        return view('admin.competitions.testcases.index', compact('competition', 'testcases'));
    }

    public function create(Competition $competition)
    {
        return view('admin.competitions.testcases.create', compact('competition'));
    }

    public function store(Request $request, Competition $competition)
    {
        $request->validate([
            'input' => 'nullable|string',
            'expected_output' => 'required|string',
        ]);
        $competition->testCases()->create($request->only('input', 'expected_output'));
        return redirect()->route('admin.competitions.testcases.index', $competition)->with('success', 'Cas de test ajouté !');
    }

    public function edit(Competition $competition, CompetitionTestCase $testcase)
    {
        return view('admin.competitions.testcases.edit', compact('competition', 'testcase'));
    }

    public function update(Request $request, Competition $competition, CompetitionTestCase $testcase)
    {
        $request->validate([
            'input' => 'nullable|string',
            'expected_output' => 'required|string',
        ]);
        $testcase->update($request->only('input', 'expected_output'));
        return redirect()->route('admin.competitions.testcases.index', $competition)->with('success', 'Cas de test modifié !');
    }

    public function destroy(Competition $competition, CompetitionTestCase $testcase)
    {
        $testcase->delete();
        return redirect()->route('admin.competitions.testcases.index', $competition)->with('success', 'Cas de test supprimé !');
    }
} 