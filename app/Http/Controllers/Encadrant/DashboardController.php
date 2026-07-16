<?php

namespace App\Http\Controllers\Encadrant;

use App\Http\Controllers\Controller;
use App\Models\AffectationStage;
use App\Models\Tache;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $encadrantId = Auth::id();

        $mesStagiaires = AffectationStage::where('id_encadrant', $encadrantId)
            ->where('active', true)
            ->count();

        $tachesEnCours = Tache::where('id_encadrant', $encadrantId)
            ->where('statut', 'En cours')
            ->count();

        $tachesEnRetard = Tache::where('id_encadrant', $encadrantId)
            ->where('date_echeance', '<', now())
            ->whereNotIn('statut', ['Terminée', 'Annulée'])
            ->count();

        $tachesTerminees = Tache::where('id_encadrant', $encadrantId)
            ->where('statut', 'Terminée')
            ->count();

        return view('encadrant.dashboard', compact(
            'mesStagiaires', 'tachesEnCours', 'tachesEnRetard', 'tachesTerminees'
        ));
    }
}