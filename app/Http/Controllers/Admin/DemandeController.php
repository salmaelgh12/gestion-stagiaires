<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\Utilisateur;
use App\Models\Stagiaire;
use Illuminate\Http\Request;

class DemandeController extends Controller
{
    public function index(Request $request)
    {
        $query = Demande::with('stagiaire.utilisateur');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('type')) {
            $query->where('type_demande', $request->type);
        }

        $demandes = $query->orderBy('date_demande', 'desc')->paginate(10)->withQueryString();

        $totalUtilisateurs = Utilisateur::count();
        $totalStagiaires = Stagiaire::count();
        $stagiairesActifs = Stagiaire::where('statut', 'En cours')->count();
        $demandesEnAttente = Demande::where('statut', 'En attente')->count();

        return view('admin.demandes.index', compact(
            'demandes', 'totalUtilisateurs', 'totalStagiaires', 'stagiairesActifs', 'demandesEnAttente'
        ));
    }

    public function show(Demande $demande)
    {
        $demande->load('stagiaire.utilisateur', 'validations.validateur');
        return view('admin.demandes.show', compact('demande'));
    }
}