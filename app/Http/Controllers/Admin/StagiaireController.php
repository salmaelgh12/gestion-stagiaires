<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stagiaire;
use App\Models\Utilisateur;
use App\Models\Demande;
use Illuminate\Http\Request;

class StagiaireController extends Controller
{
    public function index(Request $request)
{
    $query = Stagiaire::with('utilisateur');

    // Filtre par statut
    if ($request->filled('statut')) {
        $query->where('statut', $request->statut);
    }

    // Recherche par nom/prénom/email
    if ($request->filled('recherche')) {
        $recherche = $request->recherche;
        $query->whereHas('utilisateur', function ($q) use ($recherche) {
            $q->where('nom', 'like', "%{$recherche}%")
              ->orWhere('prenom', 'like', "%{$recherche}%")
              ->orWhere('email', 'like', "%{$recherche}%");
        });
    }

    $stagiaires = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

    $totalUtilisateurs = Utilisateur::count();
    $totalStagiaires = Stagiaire::count();
    $stagiairesActifs = $stagiaires->filter(fn($s) => $s->statut_calcule === 'En cours')->count();
    $demandesEnAttente = Demande::where('statut', 'En attente')->count();

    return view('admin.stagiaires.index', compact(
        'stagiaires', 'totalUtilisateurs', 'totalStagiaires', 'stagiairesActifs', 'demandesEnAttente'
    ));
}

    public function edit(Stagiaire $stagiaire)
    {
        $stagiaire->load('utilisateur');
        return view('admin.stagiaires.edit', compact('stagiaire'));
    }

    public function update(Request $request, Stagiaire $stagiaire)
    {
        $request->validate([
            'ecole' => 'nullable|string|max:255',
            'filiere' => 'nullable|string|max:255',
            'niveau_etude' => 'nullable|string|max:255',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date',
            'statut' => 'required|in:En attente,En cours,Terminé,Prolongé,Archivé',
            'score_global' => 'nullable|integer|min:0|max:100',
        ]);

        $stagiaire->update($request->only([
            'ecole', 'filiere', 'niveau_etude', 'date_debut', 'date_fin', 'statut', 'score_global'
        ]));

        return redirect()->route('admin.stagiaires.index')->with('success', 'Stagiaire mis à jour avec succès.');
    }
}