<?php

namespace App\Http\Controllers\Stagiaire;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandeController extends Controller
{
    public function index()
    {
        $stagiaire = Auth::user()->stagiaire;
        $demandes = Demande::where('id_stagiaire', $stagiaire->id_stagiaire)
            ->orderBy('date_demande', 'desc')
            ->get();

        return view('stagiaire.demandes.index', compact('demandes', 'stagiaire'));
    }

    public function create()
    {
        return view('stagiaire.demandes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_demande' => 'required|in:Attestation,Prolongation,Absence,Autre',
            'objet' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $stagiaire = Auth::user()->stagiaire;

        Demande::create([
            'id_stagiaire' => $stagiaire->id_stagiaire,
            'type_demande' => $request->type_demande,
            'objet' => $request->objet,
            'description' => $request->description,
            'statut' => 'En attente',
            'date_demande' => now(),
        ]);

        return redirect()->route('stagiaire.demandes.index')->with('success', 'Votre demande a été envoyée avec succès.');
    }
}