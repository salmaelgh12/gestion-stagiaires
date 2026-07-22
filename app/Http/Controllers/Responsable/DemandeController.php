<?php

namespace App\Http\Controllers\Responsable;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\ValidationDemande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandeController extends Controller
{
    public function index()
    {
        // Demandes validées par l'Encadrant, en attente de la validation Responsable
        $demandes = Demande::with('stagiaire.utilisateur', 'validations')
            ->whereHas('validations', function ($q) {
                $q->where('role_validateur', 'Encadrant')->where('decision', 'Validée');
            })
            ->whereDoesntHave('validations', function ($q) {
                $q->where('role_validateur', 'Responsable de compétence');
            })
            ->orderBy('date_demande', 'desc')
            ->paginate(10);

        return view('responsable.demandes.index', compact('demandes'));
    }

    public function show(Demande $demande)
    {
        $demande->load('stagiaire.utilisateur', 'validations.validateur');
        return view('responsable.demandes.show', compact('demande'));
    }

    public function valider(Request $request, Demande $demande)
    {
        ValidationDemande::create([
            'id_demande' => $demande->id_demande,
            'id_validateur' => Auth::id(),
            'role_validateur' => 'Responsable de compétence',
            'decision' => 'Validée',
            'commentaire' => $request->commentaire,
            'date_validation' => now(),
        ]);

        return redirect()->route('responsable.demandes.index')->with('success', 'Demande validée, transmise au RH.');
    }

    public function rejeter(Request $request, Demande $demande)
    {
        ValidationDemande::create([
            'id_demande' => $demande->id_demande,
            'id_validateur' => Auth::id(),
            'role_validateur' => 'Responsable de compétence',
            'decision' => 'Rejetée',
            'commentaire' => $request->commentaire,
            'date_validation' => now(),
        ]);

        $demande->update(['statut' => 'Rejetée']);

        return redirect()->route('responsable.demandes.index')->with('success', 'Demande rejetée.');
    }
}