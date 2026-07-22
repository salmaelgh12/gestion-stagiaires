<?php

namespace App\Http\Controllers;

use App\Models\AffectationStage;
use App\Models\Message;
use App\Models\Stagiaire;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $moi = Auth::id();

        // Utilisateurs avec qui j'ai déjà échangé au moins un message
        $utilisateursAvecHistorique = Utilisateur::where('id_user', '!=', $moi)
            ->whereHas('messagesRecus', fn($q) => $q->where('expediteur_id', $moi))
            ->orWhereHas('messagesEnvoyes', fn($q) => $q->where('destinataire_id', $moi))
            ->get();

        // Contacts autorisés selon le RBAC (encadrant/responsable/RH pour un stagiaire, etc.)
        $contactsAutorises = $this->contactsAutorises();

        // Fusion en une seule liste façon Teams : tout le monde qu'on a le droit de contacter,
        // qu'une conversation existe déjà ou non. On ne perd jamais l'historique d'une
        // conversation déjà entamée même si le contact n'est plus autorisé aujourd'hui.
        $contacts = $utilisateursAvecHistorique->merge($contactsAutorises)
            ->unique('id_user')
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
            ->sortByDesc(fn($c) => $c->dernier_message->date_envoi ?? now()->subYears(50))
            ->values();

        return view('messages.index', compact('contacts'));
    }

    public function show(Utilisateur $utilisateur)
    {
        $moi = Auth::id();

        $this->autoriserContact($utilisateur);

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
        $this->autoriserContact($utilisateur);

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

    /**
     * Bloque l'accès à un contact si celui-ci n'est ni un contact autorisé pour le rôle
     * de l'utilisateur connecté, ni un contact avec qui une conversation existe déjà
     * (pour ne jamais casser l'historique d'une conversation légitime déjà entamée).
     */
    private function autoriserContact(Utilisateur $utilisateur): void
    {
        $moi = Auth::id();

        if ($utilisateur->id_user === $moi) {
            abort(403);
        }

        $estAutorise = $this->contactsAutorises()->contains('id_user', $utilisateur->id_user);

        $conversationExistante = Message::where(function ($q) use ($moi, $utilisateur) {
                $q->where('expediteur_id', $moi)->where('destinataire_id', $utilisateur->id_user);
            })->orWhere(function ($q) use ($moi, $utilisateur) {
                $q->where('expediteur_id', $utilisateur->id_user)->where('destinataire_id', $moi);
            })->exists();

        if (!$estAutorise && !$conversationExistante) {
            abort(403, "Vous n'êtes pas autorisé à contacter cet utilisateur.");
        }
    }

    /**
     * Détermine la liste des utilisateurs que l'utilisateur connecté est autorisé à
     * contacter en messagerie interne, selon son rôle (RBAC) :
     *  - Stagiaire     : son encadrant, son responsable de compétence, les RH, les Admins
     *  - Encadrant     : ses stagiaires affectés, les responsables liés, les RH, les Admins
     *  - Responsable   : ses stagiaires affectés, les encadrants liés, les RH, les Admins
     *  - RH / Admin    : tous les utilisateurs actifs
     */
    private function contactsAutorises()
    {
        $moi = Auth::user();
        $role = $moi->role->nom_role ?? null;

        if (in_array($role, ['Admin', 'RH'])) {
            return Utilisateur::where('id_user', '!=', $moi->id_user)
                ->where('actif', true)
                ->get();
        }

        $rhEtAdminIds = Utilisateur::whereHas('role', function ($q) {
                $q->whereIn('nom_role', ['RH', 'Admin']);
            })
            ->where('actif', true)
            ->pluck('id_user');

        $idsAutorises = collect();

        if ($role === 'Stagiaire') {
            $stagiaire = $moi->stagiaire;

            if ($stagiaire) {
                $affectation = $stagiaire->affectations()->where('active', true)->first();

                if ($affectation) {
                    $idsAutorises->push($affectation->id_encadrant);
                    $idsAutorises->push($affectation->id_responsable_competence);
                }
            }
        } elseif ($role === 'Encadrant') {
            $affectations = AffectationStage::where('id_encadrant', $moi->id_user)
                ->where('active', true)
                ->get();

            $stagiaireUserIds = Stagiaire::whereIn('id_stagiaire', $affectations->pluck('id_stagiaire'))
                ->pluck('id_user');

            $idsAutorises = $idsAutorises
                ->merge($stagiaireUserIds)
                ->merge($affectations->pluck('id_responsable_competence'));
        } elseif ($role === 'Responsable de compétence') {
            $affectations = AffectationStage::where('id_responsable_competence', $moi->id_user)
                ->where('active', true)
                ->get();

            $stagiaireUserIds = Stagiaire::whereIn('id_stagiaire', $affectations->pluck('id_stagiaire'))
                ->pluck('id_user');

            $idsAutorises = $idsAutorises
                ->merge($stagiaireUserIds)
                ->merge($affectations->pluck('id_encadrant'));
        }

        $idsAutorises = $idsAutorises->merge($rhEtAdminIds)->filter()->unique();

        return Utilisateur::whereIn('id_user', $idsAutorises)
            ->where('actif', true)
            ->get();
    }
}