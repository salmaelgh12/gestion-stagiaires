<?php

namespace App\Http\Controllers\Encadrant;

use App\Http\Controllers\Controller;
use App\Models\AffectationStage;
use Illuminate\Support\Facades\Auth;

class StagiaireController extends Controller
{
    public function index()
    {
        $affectations = AffectationStage::with('stagiaire.utilisateur', 'stage')
            ->where('id_encadrant', Auth::id())
            ->where('active', true)
            ->get();

        return view('encadrant.stagiaires.index', compact('affectations'));
    }
}