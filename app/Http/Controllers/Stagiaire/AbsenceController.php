<?php

namespace App\Http\Controllers\Stagiaire;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsenceController extends Controller
{
    public function index()
    {
        $stagiaire = Auth::user()->stagiaire;
        $absences = Absence::where('id_stagiaire', $stagiaire->id_stagiaire)
            ->orderBy('date_absence', 'desc')
            ->get();

        return view('stagiaire.absences.index', compact('absences'));
    }

    public function create()
    {
        return view('stagiaire.absences.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_absence' => 'required|date',
            'type_absence' => 'required|string|max:100',
            'motif' => 'nullable|string',
        ]);

        $stagiaire = Auth::user()->stagiaire;

        Absence::create([
            'id_stagiaire' => $stagiaire->id_stagiaire,
            'date_absence' => $request->date_absence,
            'type_absence' => $request->type_absence,
            'justifiee' => false,
            'motif' => $request->motif,
        ]);

        return redirect()->route('stagiaire.absences.index')->with('success', 'Absence déclarée avec succès.');
    }
}
