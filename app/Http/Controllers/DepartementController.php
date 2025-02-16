<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departement;

class DepartementController extends Controller
{
    public function index()
    {
        $departements = Departement::all();
        return view('departements.index', compact('departements'));
    }

    public function create()
    {
        return view('departements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        Departement::create($request->all());

        return redirect()->route('departements.index')->with('success', 'Departement added successfully.');
    }

    public function edit($id)
    {
        $departement = Departement::findOrFail($id);
        return view('departements.edit', compact('departement'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $departement = Departement::findOrFail($id);
        $departement->update($request->all());

        return redirect()->route('departements.index')->with('success', 'Departement updated successfully.');
    }

    public function destroy($id)
    {
        $departement = Departement::findOrFail($id);
        $departement->delete();

        return redirect()->route('departements.index')->with('success', 'Departement deleted successfully.');
    }
}
