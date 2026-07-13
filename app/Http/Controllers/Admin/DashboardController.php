<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\Stagiaire;
use App\Models\Demande;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUtilisateurs = Utilisateur::count();
        $totalStagiaires = Stagiaire::count();
        $stagiairesActifs = Stagiaire::where('statut', 'En cours')->count();
        $demandesEnAttente = Demande::where('statut', 'En attente')->count();

        return view('admin.dashboard', compact(
            'totalUtilisateurs',
            'totalStagiaires',
            'stagiairesActifs',
            'demandesEnAttente'
        ));
    }
}