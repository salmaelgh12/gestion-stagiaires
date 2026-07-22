@extends('layouts.dashboard')

@section('title', 'Messagerie - STAGE-UP')
@section('page-title', 'Messagerie')
@section('page-subtitle', 'Vos conversations')

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
        display: flex;
        flex-direction: column;
    }
    .contact-item {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        text-decoration: none;
        color: inherit;
        transition: background 0.15s;
    }
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
    .contact-badge {
        background: #0F6E56; color: white; font-size: 0.7rem;
        border-radius: 50%; width: 20px; height: 20px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .empty-panel {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border-radius: 16px;
        border: 1px solid #eef0f4;
        color: #94a3b8;
        flex-direction: column;
        gap: 1rem;
    }
    .empty-panel i { font-size: 3rem; }

    .filters-bar { padding: 1rem; border-bottom: 1px solid #f1f5f9; }
    .filters-bar .filters-row { display: flex; gap: 0.5rem; }
    .filters-bar input[type="text"] {
        flex: 1; border-radius: 10px; border: 1px solid #e2e8f0; padding: 0.5rem 0.8rem; font-size: 0.85rem;
    }
    .filters-bar select {
        width: 130px; border-radius: 10px; border: 1px solid #e2e8f0; padding: 0.5rem 0.4rem; font-size: 0.8rem;
    }
    .contact-list { flex: 1; overflow-y: auto; }
    .no-result { padding: 1rem; text-align: center; color: #94a3b8; font-size: 0.85rem; }
</style>

<div class="messagerie-wrapper">
    <div class="contacts-panel">
        <div class="filters-bar">
            <div class="filters-row">
                <input type="text" id="searchContact" placeholder="Rechercher un nom..." autocomplete="off">
                <select id="roleFilter">
                    <option value="">Tous les rôles</option>
                    @foreach($contacts->pluck('role.nom_role')->unique()->filter() as $roleNom)
                        <option value="{{ $roleNom }}">{{ $roleNom }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="contact-list" id="contactList">
            @forelse($contacts as $c)
            <a href="{{ route('messages.show', $c->id_user) }}" class="contact-item"
               data-nom="{{ mb_strtolower($c->prenom.' '.$c->nom) }}" data-role="{{ $c->role->nom_role ?? '' }}">
                <div class="contact-avatar">{{ substr($c->prenom, 0, 1) }}{{ substr($c->nom, 0, 1) }}</div>
                <div class="contact-info">
                    <div class="contact-name">{{ $c->prenom }} {{ $c->nom }}</div>
                    <div class="contact-preview">{{ $c->dernier_message->contenu ?? ($c->role->nom_role ?? '') }}</div>
                </div>
                @if($c->non_lus > 0)
                    <div class="contact-badge">{{ $c->non_lus }}</div>
                @endif
            </a>
            @empty
            <div class="no-result">Aucun contact disponible.</div>
            @endforelse
            <div class="no-result" id="noResultMsg" style="display:none;">Aucun résultat.</div>
        </div>
    </div>

    <div class="empty-panel">
        <i class="bi bi-chat-square-text"></i>
        <div>Sélectionnez une conversation pour l'afficher</div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var searchInput = document.getElementById('searchContact');
        var roleFilter = document.getElementById('roleFilter');
        var noResultMsg = document.getElementById('noResultMsg');
        var items = document.querySelectorAll('#contactList .contact-item');

        function applyFilters() {
            var texte = searchInput.value.trim().toLowerCase();
            var role = roleFilter.value;
            var visibleCount = 0;

            items.forEach(function (item) {
                var nomMatch = !texte || item.dataset.nom.indexOf(texte) !== -1;
                var roleMatch = !role || item.dataset.role === role;
                var visible = nomMatch && roleMatch;
                item.style.display = visible ? 'flex' : 'none';
                if (visible) visibleCount++;
            });

            noResultMsg.style.display = (visibleCount === 0 && items.length > 0) ? 'block' : 'none';
        }

        searchInput.addEventListener('input', applyFilters);
        roleFilter.addEventListener('change', applyFilters);
    });
</script>
@endsection