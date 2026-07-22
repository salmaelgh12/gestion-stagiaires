<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{
    public function index()
    {
        $utilisateurs = Utilisateur::with('role')->orderBy('created_at', 'desc')->get();
        $totalUtilisateurs = Utilisateur::count();
        $totalStagiaires = \App\Models\Stagiaire::count();
        $stagiairesActifs = \App\Models\Stagiaire::where('statut', 'En cours')->count();
        $demandesEnAttente = \App\Models\Demande::where('statut', 'En attente')->count();

        return view('admin.utilisateurs.index', compact(
            'utilisateurs', 'totalUtilisateurs', 'totalStagiaires', 'stagiairesActifs', 'demandesEnAttente'
        ));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.utilisateurs.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'telephone' => 'nullable|digits:10',
            'mot_de_passe' => 'required|min:6',
            'id_role' => 'required|exists:roles,id_role',
        ], [
            'telephone.digits' => 'Le numéro de téléphone doit contenir exactement 10 chiffres.',
        ]);

        $utilisateur = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'mot_de_passe' => Hash::make($request->mot_de_passe),
            'id_role' => $request->id_role,
            'actif' => true,
        ]);

        $role = Role::find($request->id_role);

        if ($role && $role->nom_role === 'Stagiaire') {
            \App\Models\Stagiaire::create([
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
        }

        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(Utilisateur $utilisateur)
    {
        $roles = Role::all();
        return view('admin.utilisateurs.edit', compact('utilisateur', 'roles'));
    }

    public function update(Request $request, Utilisateur $utilisateur)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email,' . $utilisateur->id_user . ',id_user',
            'telephone' => 'nullable|digits:10',
            'id_role' => 'required|exists:roles,id_role',
        ], [
            'telephone.digits' => 'Le numéro de téléphone doit contenir exactement 10 chiffres.',
        ]);

        $utilisateur->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'id_role' => $request->id_role,
        ]);

        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur modifié avec succès.');
    }

    public function toggleActive(Utilisateur $utilisateur)
    {
        $utilisateur->update(['actif' => !$utilisateur->actif]);
        return back()->with('success', 'Statut mis à jour.');
    }

    public function destroy(Utilisateur $utilisateur)
    {
        if ($utilisateur->stagiaire) {
            $utilisateur->stagiaire->delete();
        }

        $utilisateur->delete();

        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur supprimé.');
    }

    public function showResetPassword(Utilisateur $utilisateur)
    {
        return view('admin.utilisateurs.reset-password', compact('utilisateur'));
    }

    public function resetPassword(Request $request, Utilisateur $utilisateur)
    {
        $request->validate([
            'mot_de_passe' => 'required|min:6|confirmed',
        ]);

        $utilisateur->update([
            'mot_de_passe' => Hash::make($request->mot_de_passe),
        ]);

        return redirect()->route('admin.utilisateurs.index')->with('success', 'Mot de passe modifié avec succès pour ' . $utilisateur->prenom . ' ' . $utilisateur->nom . '.');
    }
}