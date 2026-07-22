<?php

namespace App\Http\Controllers\Responsable;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use Illuminate\Http\Request;

class StageController extends Controller
{
    public function index()
    {
        $stages = Stage::orderBy('created_at', 'desc')->paginate(10);
        return view('responsable.stages.index', compact('stages'));
    }

    public function create()
    {
        return view('responsable.stages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'objectif' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'departement' => 'nullable|string|max:255',
        ]);

        Stage::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'objectif' => $request->objectif,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'departement' => $request->departement,
            'statut' => 'Ouvert',
        ]);

        return redirect()->route('responsable.stages.index')->with('success', 'Stage créé avec succès.');
    }
}