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

    .new-contact-select { padding: 1rem; border-bottom: 1px solid #f1f5f9; position: relative; }
    .new-contact-select .filters-row { display: flex; gap: 0.5rem; }
    .new-contact-select input[type="text"] {
        flex: 1; border-radius: 10px; border: 1px solid #e2e8f0; padding: 0.5rem 0.8rem; font-size: 0.85rem;
    }
    .new-contact-select select {
        width: 130px; border-radius: 10px; border: 1px solid #e2e8f0; padding: 0.5rem 0.4rem; font-size: 0.8rem;
    }
    .search-results {
        display: none;
        position: absolute;
        left: 1rem; right: 1rem; top: calc(100% - 0.4rem);
        background: white;
        border: 1px solid #eef0f4;
        border-radius: 10px;
        box-shadow: 0 10px 24px rgba(0,0,0,0.1);
        max-height: 280px;
        overflow-y: auto;
        z-index: 50;
    }
    .search-results .contact-item { padding: 0.7rem 1rem; }
    .search-results .no-result { padding: 1rem; text-align: center; color: #94a3b8; font-size: 0.85rem; }
</style>

<div class="messagerie-wrapper">
    <div class="contacts-panel">
        <div class="new-contact-select">
            <div class="filters-row">
                <input type="text" id="searchContact" placeholder="Rechercher un nom..." autocomplete="off">
                <select id="roleFilter">
                    <option value="">Tous les rôles</option>
                    @foreach($tousLesUtilisateurs->pluck('role.nom_role')->unique()->filter() as $roleNom)
                        <option value="{{ $roleNom }}">{{ $roleNom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="search-results" id="searchResults">
                @forelse($tousLesUtilisateurs as $u)
                <a href="{{ route('messages.show', $u->id_user) }}" class="contact-item"
                   data-nom="{{ mb_strtolower($u->prenom.' '.$u->nom) }}" data-role="{{ $u->role->nom_role }}">
                    <div class="contact-avatar">{{ substr($u->prenom, 0, 1) }}{{ substr($u->nom, 0, 1) }}</div>
                    <div class="contact-info">
                        <div class="contact-name">{{ $u->prenom }} {{ $u->nom }}</div>
                        <div class="contact-preview">{{ $u->role->nom_role }}</div>
                    </div>
                </a>
                @empty
                <div class="no-result">Aucun contact disponible.</div>
                @endforelse
                <div class="no-result" id="noResultMsg" style="display:none;">Aucun résultat.</div>
            </div>
        </div>

        @forelse($contacts as $c)
        <a href="{{ route('messages.show', $c->id_user) }}" class="contact-item">
            <div class="contact-avatar">{{ substr($c->prenom, 0, 1) }}{{ substr($c->nom, 0, 1) }}</div>
            <div class="contact-info">
                <div class="contact-name">{{ $c->prenom }} {{ $c->nom }}</div>
                <div class="contact-preview">{{ $c->dernier_message->contenu ?? '' }}</div>
            </div>
            @if($c->non_lus > 0)
                <div class="contact-badge">{{ $c->non_lus }}</div>
            @endif
        </a>
        @empty
        <div class="text-center text-muted p-4" style="font-size:0.9rem;">Aucune conversation. Sélectionnez un contact ci-dessus pour commencer.</div>
        @endforelse
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
        var resultsPanel = document.getElementById('searchResults');
        var noResultMsg = document.getElementById('noResultMsg');
        var items = resultsPanel.querySelectorAll('.contact-item');

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

        searchInput.addEventListener('input', function () {
            resultsPanel.style.display = 'block';
            applyFilters();
        });

        searchInput.addEventListener('focus', function () {
            resultsPanel.style.display = 'block';
            applyFilters();
        });

        roleFilter.addEventListener('change', function () {
            resultsPanel.style.display = 'block';
            applyFilters();
        });

        document.addEventListener('click', function (e) {
            if (!resultsPanel.contains(e.target) && e.target !== searchInput && e.target !== roleFilter) {
                resultsPanel.style.display = 'none';
            }
        });
    });
</script>
@endsection