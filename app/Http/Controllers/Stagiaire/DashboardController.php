<?php

namespace App\Http\Controllers\Stagiaire;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stagiaire = Auth::user()->stagiaire;

        $joursRestants = 0;
        if ($stagiaire && $stagiaire->date_fin) {
            $aujourdhui = Carbon::now()->startOfDay();
            $fin = Carbon::parse($stagiaire->date_fin)->startOfDay();

            if ($aujourdhui->lte($fin)) {
                $joursRestants = $aujourdhui->diffInDays($fin);
            }
        }

        // Contacts initiaux du stagiaire : encadrant + responsable (via son affectation
        // active) et les RH, pour qu'il sache à qui s'adresser dans la messagerie interne.
        $monEncadrant = null;
        $monResponsable = null;

        if ($stagiaire) {
            $affectation = $stagiaire->affectations()
                ->where('active', true)
                ->with('encadrant', 'responsableCompetence')
                ->latest('date_affectation')
                ->first();

            if ($affectation) {
                $monEncadrant = $affectation->encadrant;
                $monResponsable = $affectation->responsableCompetence;
            }
        }

        $contactsRh = Utilisateur::whereHas('role', function ($q) {
                $q->where('nom_role', 'RH');
            })
            ->where('actif', true)
            ->get();

        return view('stagiaire.dashboard', compact(
            'stagiaire', 'joursRestants', 'monEncadrant', 'monResponsable', 'contactsRh'
        ));
    }
}