<?php

namespace App\Http\Controllers\RH;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\Attestation;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $demandesAValider = Demande::whereHas('validations', function ($q) {
                $q->where('role_validateur', 'Responsable de compétence')->where('decision', 'Validée');
            })
            ->whereDoesntHave('validations', function ($q) {
                $q->where('role_validateur', 'RH');
            })
            ->count();

        $attestationsGenerees = Attestation::count();
        $validationsFaites = \App\Models\ValidationDemande::where('id_validateur', Auth::id())->count();

        return view('rh.dashboard', compact('demandesAValider', 'attestationsGenerees', 'validationsFaites'));
    }
}