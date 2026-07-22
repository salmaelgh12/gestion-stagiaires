<?php

namespace App\Http\Controllers\RH;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\ValidationDemande;
use App\Models\Attestation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandeController extends Controller
{
    public function index()
    {
        $demandes = Demande::with('stagiaire.utilisateur', 'validations')
            ->whereHas('validations', function ($q) {
                $q->where('role_validateur', 'Responsable de compétence')->where('decision', 'Validée');
            })
            ->whereDoesntHave('validations', function ($q) {
                $q->where('role_validateur', 'RH');
            })
            ->orderBy('date_demande', 'desc')
            ->paginate(10);

        return view('rh.demandes.index', compact('demandes'));
    }

    public function show(Demande $demande)
    {
        $demande->load('stagiaire.utilisateur', 'validations.validateur');
        return view('rh.demandes.show', compact('demande'));
    }

    public function valider(Request $request, Demande $demande)
    {
        ValidationDemande::create([
            'id_demande' => $demande->id_demande,
            'id_validateur' => Auth::id(),
            'role_validateur' => 'RH',
            'decision' => 'Validée',
            'commentaire' => $request->commentaire,
            'date_validation' => now(),
        ]);

        $demande->update(['statut' => 'Traitée']);

        // Si c'est une demande d'attestation, on la génère automatiquement
        if ($demande->type_demande === 'Attestation') {
            Attestation::create([
                'id_stagiaire' => $demande->id_stagiaire,
                'id_demande' => $demande->id_demande,
                'numero_attestation' => 'ATT-' . date('Y') . '-' . str_pad($demande->id_demande, 4, '0', STR_PAD_LEFT),
                'date_generation' => now(),
                'date_validation_rh' => now(),
                'statut' => 'Générée',
            ]);
        }

        return redirect()->route('rh.demandes.index')->with('success', 'Demande validée et traitée avec succès.');
    }

    public function rejeter(Request $request, Demande $demande)
    {
        ValidationDemande::create([
            'id_demande' => $demande->id_demande,
            'id_validateur' => Auth::id(),
            'role_validateur' => 'RH',
            'decision' => 'Rejetée',
            'commentaire' => $request->commentaire,
            'date_validation' => now(),
        ]);

        $demande->update(['statut' => 'Rejetée']);

        return redirect()->route('rh.demandes.index')->with('success', 'Demande rejetée.');
    }
}