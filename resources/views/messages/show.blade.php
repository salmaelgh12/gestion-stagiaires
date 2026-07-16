@extends('layouts.dashboard')

@section('title', 'Conversation - STAGE-UP')
@section('page-title', 'Messagerie')
@section('page-subtitle', $utilisateur->prenom . ' ' . $utilisateur->nom)

@section('tabs')
@endsection

@section('content')
<style>
    .messagerie-wrapper { display: flex; gap: 1.5rem; height: calc(100vh - 260px); min-height: 400px; }
    .contacts-panel {
        width: 320px;
        background: white;
        border-radius: 16px;
        border: 1px solid #eef0f4;
        overflow-y: auto;
        flex-shrink: 0;
    }
    .contact-item {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        text-decoration: none;
        color: inherit;
    }
    .contact-item.active { background: #eafaf5; }
    .contact-item:hover { background: #f8fafc; }
    .contact-avatar {
        width: 42px; height: 42px; border-radius: 50%;
        background: #eafaf5; color: #0F6E56;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.9rem; flex-shrink: 0;
    }
    .contact-info { flex: 1; min-width: 0; }
    .contact-name { font-weight: 600; color: #1e293b; font-size: 0.9rem; }
    .contact-preview { color: #64748b; font-size: 0.8rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .chat-panel {
        flex: 1;
        background: white;
        border-radius: 16px;
        border: 1px solid #eef0f4;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .chat-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #eef0f4;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    .chat-body {
        flex: 1;
        padding: 1.5rem;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
        background: #f8fafc;
    }
    .chat-msg {
        max-width: 65%;
        padding: 0.7rem 1rem;
        border-radius: 14px;
        font-size: 0.9rem;
        line-height: 1.4;
    }
    .chat-msg.moi {
        align-self: flex-end;
        background: #0F6E56;
        color: white;
        border-bottom-right-radius: 4px;
    }
    .chat-msg.autre {
        align-self: flex-start;
        background: white;
        color: #1e293b;
        border: 1px solid #eef0f4;
        border-bottom-left-radius: 4px;
    }
    .chat-msg .time { font-size: 0.7rem; opacity: 0.7; margin-top: 0.3rem; display: block; }
    .chat-footer {
        padding: 1rem;
        border-top: 1px solid #eef0f4;
        display: flex;
        gap: 0.6rem;
    }
    .chat-footer input {
        flex: 1;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 0.6rem 1.1rem;
        font-size: 0.9rem;
    }
    .chat-footer button {
        background: #0F6E56;
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px; height: 40px;
    }
</style>

<div class="messagerie-wrapper">
    <div class="contacts-panel">
        @foreach($tousLesUtilisateurs ?? \App\Models\Utilisateur::where('id_user', '!=', auth()->id())->get() as $u)
        <a href="{{ route('messages.show', $u->id_user) }}" class="contact-item {{ $u->id_user == $utilisateur->id_user ? 'active' : '' }}">
            <div class="contact-avatar">{{ substr($u->prenom, 0, 1) }}{{ substr($u->nom, 0, 1) }}</div>
            <div class="contact-info">
                <div class="contact-name">{{ $u->prenom }} {{ $u->nom }}</div>
                <div class="contact-preview">{{ $u->role->nom_role ?? '' }}</div>
            </div>
        </a>
        @endforeach
    </div>

    <div class="chat-panel">
        <div class="chat-header">
            <div class="contact-avatar">{{ substr($utilisateur->prenom, 0, 1) }}{{ substr($utilisateur->nom, 0, 1) }}</div>
            <div>
                <div style="font-weight:700; color:#1e293b;">{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</div>
                <div style="font-size:0.8rem; color:#64748b;">{{ $utilisateur->role->nom_role ?? '' }}</div>
            </div>
        </div>

        <div class="chat-body" id="chatBody">
            @forelse($messages as $m)
                <div class="chat-msg {{ $m->expediteur_id == auth()->id() ? 'moi' : 'autre' }}">
                    {{ $m->contenu }}
                    <span class="time">{{ \Carbon\Carbon::parse($m->date_envoi)->format('d/m H:i') }}</span>
                </div>
            @empty
                <div class="text-center text-muted py-4">Aucun message. Démarrez la conversation !</div>
            @endforelse
        </div>

        <form method="POST" action="{{ route('messages.store', $utilisateur->id_user) }}" class="chat-footer">
            @csrf
            <input type="text" name="contenu" placeholder="Écrivez un message..." required autocomplete="off">
            <button type="submit"><i class="bi bi-send-fill"></i></button>
        </form>
    </div>
</div>

<script>
    var chatBody = document.getElementById('chatBody');
    if (chatBody) chatBody.scrollTop = chatBody.scrollHeight;
</script>
@endsection