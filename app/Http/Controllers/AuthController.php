<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        $roles = Role::all();
        return view('auth.register', compact('roles'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'mot_de_passe' => 'required|min:6',
            'id_role' => 'required|exists:roles,id_role',
        ]);

        Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'mot_de_passe' => Hash::make($request->mot_de_passe),
            'id_role' => $request->id_role,
            'actif' => true,
        ]);

        return redirect('/login')->with('success', 'Inscription réussie, vous pouvez vous connecter.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mot_de_passe' => 'required',
        ]);

        $utilisateur = Utilisateur::where('email', $request->email)->first();

        if (!$utilisateur || !Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
            return back()->withErrors(['email' => 'Identifiants incorrects']);
        }

        Auth::login($utilisateur);
        session()->flash('just_logged_in', true);

        if ($utilisateur->role->nom_role === 'Admin') {
            return redirect('/admin/dashboard');
        }

        if ($utilisateur->role->nom_role === 'Stagiaire') {
            return redirect('/stagiaire/dashboard');
        }

        if ($utilisateur->role->nom_role === 'Encadrant') {
            return redirect('/encadrant/dashboard');
        }

        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Un lien de réinitialisation a été envoyé à votre adresse email.')
            : back()->withErrors(['email' => 'Aucun compte trouvé avec cet email.']);
    }

    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'mot_de_passe' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'mot_de_passe', 'mot_de_passe_confirmation', 'token'),
            function ($utilisateur, $password) {
                $utilisateur->forceFill([
                    'mot_de_passe' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect('/login')->with('success', 'Votre mot de passe a été réinitialisé avec succès.')
            : back()->withErrors(['email' => 'Ce lien de réinitialisation est invalide ou expiré.']);
    }
}