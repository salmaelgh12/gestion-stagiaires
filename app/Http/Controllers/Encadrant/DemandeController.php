<?php

namespace App\Http\Controllers\Encadrant;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\ValidationDemande;
use App\Models\AffectationStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandeController extends Controller
{
    public function index()
    {
        $mesStagiairesIds = AffectationStage::where('id_encadrant', Auth::id())
            ->where('active', true)
            ->pluck('id_stagiaire');

        $demandes = Demande::with('stagiaire.utilisateur')
            ->whereIn('id_stagiaire', $mesStagiairesIds)
            ->whereDoesntHave('validations', function ($q) {
                $q->where('role_validateur', 'Encadrant');
            })
            ->orderBy('date_demande', 'desc')
            ->paginate(10);

        return view('encadrant.demandes.index', compact('demandes'));
    }

    public function show(Demande $demande)
    {
        $demande->load('stagiaire.utilisateur', 'validations');
        return view('encadrant.demandes.show', compact('demande'));
    }

    public function valider(Request $request, Demande $demande)
    {
        ValidationDemande::create([
            'id_demande' => $demande->id_demande,
            'id_validateur' => Auth::id(),
            'role_validateur' => 'Encadrant',
            'decision' => 'Validée',
            'commentaire' => $request->commentaire,
            'date_validation' => now(),
        ]);

        return redirect()->route('encadrant.demandes.index')->with('success', 'Demande validée, transmise au Responsable de compétence.');
    }

    public function rejeter(Request $request, Demande $demande)
    {
        ValidationDemande::create([
            'id_demande' => $demande->id_demande,
            'id_validateur' => Auth::id(),
            'role_validateur' => 'Encadrant',
            'decision' => 'Rejetée',
            'commentaire' => $request->commentaire,
            'date_validation' => now(),
        ]);

        $demande->update(['statut' => 'Rejetée']);

        return redirect()->route('encadrant.demandes.index')->with('success', 'Demande rejetée.');
    }
}