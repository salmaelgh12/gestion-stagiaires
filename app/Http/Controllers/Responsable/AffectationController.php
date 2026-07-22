<?php

namespace App\Http\Controllers\Responsable;

use App\Http\Controllers\Controller;
use App\Models\AffectationStage;
use App\Models\Stagiaire;
use App\Models\Stage;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffectationController extends Controller
{
    public function index()
    {
        $affectations = AffectationStage::with('stagiaire.utilisateur', 'stage', 'encadrant')
            ->where('active', true)
            ->orderBy('date_affectation', 'desc')
            ->paginate(10);

        return view('responsable.affectations.index', compact('affectations'));
    }

    public function create()
    {
        // Stagiaires pas encore affectés activement
        $stagiairesDejaAffectesIds = AffectationStage::where('active', true)->pluck('id_stagiaire');
        $stagiaires = Stagiaire::with('utilisateur')
            ->whereNotIn('id_stagiaire', $stagiairesDejaAffectesIds)
            ->get();

        $stages = Stage::where('statut', 'Ouvert')->get();

        $encadrants = Utilisateur::whereHas('role', function ($q) {
            $q->where('nom_role', 'Encadrant');
        })->where('actif', true)->get();

        return view('responsable.affectations.create', compact('stagiaires', 'stages', 'encadrants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_stagiaire' => 'required|exists:stagiaires,id_stagiaire',
            'id_stage' => 'required|exists:stages,id_stage',
            'id_encadrant' => 'required|exists:utilisateurs,id_user',
        ]);

        AffectationStage::create([
            'id_stagiaire' => $request->id_stagiaire,
            'id_stage' => $request->id_stage,
            'id_encadrant' => $request->id_encadrant,
            'id_responsable_competence' => Auth::id(),
            'date_affectation' => now(),
            'active' => true,
        ]);

        // Met à jour le statut du stage et du stagiaire
        Stage::find($request->id_stage)->update(['statut' => 'Pourvu']);
        Stagiaire::find($request->id_stagiaire)->update(['statut' => 'En cours']);

        return redirect()->route('responsable.affectations.index')->with('success', 'Affectation créée avec succès.');
    }
}