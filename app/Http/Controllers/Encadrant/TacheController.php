<?php

namespace App\Http\Controllers\Encadrant;

use App\Http\Controllers\Controller;
use App\Models\Tache;
use App\Models\AffectationStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TacheController extends Controller
{
    public function index(Request $request)
    {
        $query = Tache::with('stagiaire.utilisateur')->where('id_encadrant', Auth::id());

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('stagiaire')) {
            $query->where('id_stagiaire', $request->stagiaire);
        }

        $taches = $query->orderBy('date_echeance', 'asc')->paginate(10)->withQueryString();

        $mesStagiairesListe = AffectationStage::with('stagiaire.utilisateur')
            ->where('id_encadrant', Auth::id())
            ->where('active', true)
            ->get()
            ->pluck('stagiaire')
            ->filter();

        return view('encadrant.taches.index', compact('taches', 'mesStagiairesListe'));
    }

    public function create()
    {
        $mesStagiaires = AffectationStage::with('stagiaire.utilisateur')
            ->where('id_encadrant', Auth::id())
            ->where('active', true)
            ->get()
            ->pluck('stagiaire')
            ->filter();

        return view('encadrant.taches.create', compact('mesStagiaires'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'id_stagiaire' => 'required|exists:stagiaires,id_stagiaire',
            'date_echeance' => 'required|date',
            'priorite' => 'required|in:Faible,Moyenne,Haute',
        ]);

        Tache::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'id_stagiaire' => $request->id_stagiaire,
            'id_encadrant' => Auth::id(),
            'date_creation' => now(),
            'date_echeance' => $request->date_echeance,
            'priorite' => $request->priorite,
            'statut' => 'A faire',
            'pourcentage_avancement' => 0,
        ]);

        return redirect()->route('encadrant.taches.index')->with('success', 'Tâche assignée avec succès.');
    }

    public function edit(Tache $tache)
    {
        if ($tache->id_encadrant !== Auth::id()) {
            abort(403);
        }

        $mesStagiaires = AffectationStage::with('stagiaire.utilisateur')
            ->where('id_encadrant', Auth::id())
            ->where('active', true)
            ->get()
            ->pluck('stagiaire')
            ->filter();

        return view('encadrant.taches.edit', compact('tache', 'mesStagiaires'));
    }

    public function update(Request $request, Tache $tache)
    {
        if ($tache->id_encadrant !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'id_stagiaire' => 'required|exists:stagiaires,id_stagiaire',
            'date_echeance' => 'required|date',
            'priorite' => 'required|in:Faible,Moyenne,Haute',
            'statut' => 'required|in:A faire,En cours,Terminée,Annulée',
            'pourcentage_avancement' => 'required|integer|min:0|max:100',
        ]);

        $tache->update($request->only([
            'titre', 'description', 'id_stagiaire', 'date_echeance', 'priorite', 'statut', 'pourcentage_avancement'
        ]));

        if ($request->statut === 'Terminée' && !$tache->date_realisation) {
            $tache->update(['date_realisation' => now()]);
        }

        return redirect()->route('encadrant.taches.index')->with('success', 'Tâche mise à jour.');
    }

    public function destroy(Tache $tache)
    {
        if ($tache->id_encadrant !== Auth::id()) {
            abort(403);
        }

        $tache->delete();
        return redirect()->route('encadrant.taches.index')->with('success', 'Tâche supprimée.');
    }
}