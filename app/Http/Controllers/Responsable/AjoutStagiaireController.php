<?php

namespace App\Http\Controllers\Responsable;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\Stagiaire;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AjoutStagiaireController extends Controller
{
    public function create()
    {
        return view('responsable.stagiaires.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'telephone' => 'nullable|digits:10',
            'mot_de_passe' => 'required|min:6',
            'ecole' => 'nullable|string|max:255',
            'filiere' => 'nullable|string|max:255',
            'niveau_etude' => 'nullable|string|max:255',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date',
        ], [
            'telephone.digits' => 'Le numéro de téléphone doit contenir exactement 10 chiffres.',
        ]);

        $roleStagiaire = Role::where('nom_role', 'Stagiaire')->first();

        $utilisateur = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'mot_de_passe' => Hash::make($request->mot_de_passe),
            'id_role' => $roleStagiaire->id_role,
            'actif' => true,
        ]);

        Stagiaire::create([
            'id_user' => $utilisateur->id_user,
            'ecole' => $request->ecole,
            'filiere' => $request->filiere,
            'niveau_etude' => $request->niveau_etude,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'statut' => 'En attente',
            'score_global' => 0,
            'archive' => false,
        ]);

        return redirect()->route('responsable.stagiaires.index')->with('success', 'Stagiaire ajouté avec succès.');
    }
}