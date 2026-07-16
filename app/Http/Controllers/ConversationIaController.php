<?php

namespace App\Http\Controllers;

use App\Models\ConversationIa;
use App\Models\MessageIa;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationIaController extends Controller
{
    public function getOrCreateConversation()
    {
        $conversation = ConversationIa::where('id_user', Auth::id())
            ->orderBy('date_derniere_activite', 'desc')
            ->first();

        if (!$conversation) {
            $conversation = ConversationIa::create([
                'id_user' => Auth::id(),
                'titre' => 'Conversation avec l\'assistant',
                'date_debut' => now(),
                'date_derniere_activite' => now(),
            ]);
        }

        return $conversation;
    }

    public function history()
    {
        $conversation = $this->getOrCreateConversation();
        $messages = MessageIa::where('id_conversation', $conversation->id_conversation)
            ->orderBy('date_envoi', 'asc')
            ->get();

        return response()->json(['messages' => $messages]);
    }

public function send(Request $request, GeminiService $gemini)
{
    $request->validate(['message' => 'required|string']);

    $conversation = $this->getOrCreateConversation();
    $utilisateur = Auth::user();

    MessageIa::create([
        'id_conversation' => $conversation->id_conversation,
        'role' => 'user',
        'contenu' => $request->message,
        'date_envoi' => now(),
    ]);

    $history = MessageIa::where('id_conversation', $conversation->id_conversation)
        ->orderBy('date_envoi', 'asc')
        ->get()
        ->toArray();

    $donneesContexte = $this->getDonneesSelonRole($utilisateur);

    $systemContext = "Tu es l'assistant virtuel de STAGE-UP, une plateforme de gestion de stagiaires. "
        . "L'utilisateur connecté s'appelle {$utilisateur->prenom} {$utilisateur->nom} et a le rôle '{$utilisateur->role->nom_role}'. "
        . "Voici les données réelles et actuelles auxquelles tu as accès pour cet utilisateur : {$donneesContexte} "
        . "Réponds de façon utile, concise, et professionnelle, en français, en te basant UNIQUEMENT sur ces données réelles. "
        . "Si une information n'est pas dans les données fournies, dis clairement que tu ne l'as pas, ne l'invente jamais.";

    $reponse = $gemini->ask($request->message, $history, $systemContext);

    MessageIa::create([
        'id_conversation' => $conversation->id_conversation,
        'role' => 'assistant',
        'contenu' => $reponse,
        'date_envoi' => now(),
    ]);

    $conversation->update(['date_derniere_activite' => now()]);

    return response()->json(['reponse' => $reponse]);
}

private function getDonneesSelonRole($utilisateur)
{
    $role = $utilisateur->role->nom_role;

    if ($role === 'Admin') {
        $totalUtilisateurs = \App\Models\Utilisateur::count();
        $totalStagiaires = \App\Models\Stagiaire::count();
        $stagiairesActifs = \App\Models\Stagiaire::where('statut', 'En cours')->count();
        $demandesEnAttente = \App\Models\Demande::where('statut', 'En attente')->count();

        return "Nombre total d'utilisateurs : {$totalUtilisateurs}. "
            . "Nombre total de stagiaires : {$totalStagiaires}. "
            . "Stagiaires actuellement actifs (statut 'En cours') : {$stagiairesActifs}. "
            . "Demandes en attente de traitement : {$demandesEnAttente}.";
    }

    // Pour les autres rôles, on renverra des données limitées à leur propre périmètre plus tard
    return "Aucune donnée spécifique disponible pour ce rôle pour le moment.";
}
}