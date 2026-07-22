<?php

namespace App\Http\Controllers\Responsable;

use App\Http\Controllers\Controller;
use App\Models\Stagiaire;
use App\Models\Demande;
use App\Models\ValidationDemande;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStagiaires = Stagiaire::count();
        $stagiairesActifs = Stagiaire::where('statut', 'En cours')->count();

        // Demandes en attente à l'étape "Responsable" (déjà validées par l'encadrant)
        $demandesAValider = Demande::whereHas('validations', function ($q) {
                $q->where('role_validateur', 'Encadrant')->where('decision', 'Validée');
            })
            ->whereDoesntHave('validations', function ($q) {
                $q->where('role_validateur', 'Responsable de compétence');
            })
            ->count();

        $validationsFaites = ValidationDemande::where('id_validateur', Auth::id())->count();

        return view('responsable.dashboard', compact(
            'totalStagiaires', 'stagiairesActifs', 'demandesAValider', 'validationsFaites'
        ));
    }
}