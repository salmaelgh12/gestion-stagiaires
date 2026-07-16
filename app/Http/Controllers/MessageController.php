<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $moi = Auth::id();

        // Liste des utilisateurs avec qui j'ai échangé, avec le dernier message et le nb de non-lus
        $contacts = Utilisateur::where('id_user', '!=', $moi)
            ->whereHas('messagesRecus', fn($q) => $q->where('expediteur_id', $moi))
            ->orWhereHas('messagesEnvoyes', fn($q) => $q->where('destinataire_id', $moi))
            ->get()
            ->map(function ($contact) use ($moi) {
                $dernierMessage = Message::where(function ($q) use ($moi, $contact) {
                        $q->where('expediteur_id', $moi)->where('destinataire_id', $contact->id_user);
                    })->orWhere(function ($q) use ($moi, $contact) {
                        $q->where('expediteur_id', $contact->id_user)->where('destinataire_id', $moi);
                    })->orderBy('date_envoi', 'desc')->first();

                $nonLus = Message::where('expediteur_id', $contact->id_user)
                    ->where('destinataire_id', $moi)
                    ->where('lu', false)
                    ->count();

                $contact->dernier_message = $dernierMessage;
                $contact->non_lus = $nonLus;
                return $contact;
            })
            ->sortByDesc(fn($c) => $c->dernier_message->date_envoi ?? now()->subYears(10));

        $tousLesUtilisateurs = Utilisateur::where('id_user', '!=', $moi)->where('actif', true)->get();

        return view('messages.index', compact('contacts', 'tousLesUtilisateurs'));
    }

    public function show(Utilisateur $utilisateur)
    {
        $moi = Auth::id();

        $messages = Message::where(function ($q) use ($moi, $utilisateur) {
                $q->where('expediteur_id', $moi)->where('destinataire_id', $utilisateur->id_user);
            })->orWhere(function ($q) use ($moi, $utilisateur) {
                $q->where('expediteur_id', $utilisateur->id_user)->where('destinataire_id', $moi);
            })->orderBy('date_envoi', 'asc')->get();

        // Marque comme lus les messages reçus de ce contact
        Message::where('expediteur_id', $utilisateur->id_user)
            ->where('destinataire_id', $moi)
            ->where('lu', false)
            ->update(['lu' => true]);

        return view('messages.show', compact('messages', 'utilisateur'));
    }

    public function store(Request $request, Utilisateur $utilisateur)
    {
        $request->validate([
            'contenu' => 'required|string',
        ]);

        Message::create([
            'expediteur_id' => Auth::id(),
            'destinataire_id' => $utilisateur->id_user,
            'objet' => null,
            'contenu' => $request->contenu,
            'lu' => false,
            'date_envoi' => now(),
        ]);

        return redirect()->route('messages.show', $utilisateur->id_user);
    }
}