@extends('layouts.dashboard')

@section('title', 'Stagiaires - STAGE-UP')
@section('page-title', 'Gestion des stagiaires')
@section('page-subtitle', 'Suivez et gérez le parcours de vos stagiaires')

@section('tabs')
    <a href="/admin/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/admin/utilisateurs"><i class="bi bi-people-fill"></i> Utilisateurs</a>
    <a href="/admin/stagiaires" class="active"><i class="bi bi-mortarboard-fill"></i> Stagiaires</a>
    <a href="/admin/demandes"><i class="bi bi-file-earmark-text-fill"></i> Demandes @if($demandesEnAttente > 0)<span class="badge-count">{{ $demandesEnAttente }}</span>@endif</a>
@endsection

@section('content')
<style>
    .table-card { background: white; border-radius: 16px; border: 1px solid #eef0f4; overflow: hidden; }
    .table-card table { margin: 0; }
    .table-card th { background: #f8fafc; font-size: 0.8rem; color: #64748b; font-weight: 700; border-bottom: 1px solid #eef0f4; padding: 1rem; }
    .table-card td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
    .avatar-sm {
        width: 36px; height: 36px; border-radius: 50%;
        background: #eef2ff; color: #4f46e5;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.8rem;
    }
    .statut-pill { font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.7rem; border-radius: 20px; display: inline-block; }
    .statut-En_attente { background: #fffbeb; color: #d97706; }
    .statut-En_cours { background: #eafaf5; color: #0F6E56; }
    .statut-Terminé { background: #eef2ff; color: #4f46e5; }
    .statut-Prolongé { background: #fdf2f8; color: #ec4899; }
    .statut-Archivé { background: #f1f5f9; color: #64748b; }
    .score-badge { font-weight: 700; color: #1e293b; }
    .btn-icon-action { border: none; background: none; color: #64748b; padding: 0.3rem 0.5rem; border-radius: 6px; text-decoration: none; }
    .btn-icon-action:hover { background: #f1f5f9; color: #1e293b; }

    .filters-bar {
        background: white; border-radius: 16px; border: 1px solid #eef0f4;
        padding: 1rem 1.2rem; margin-bottom: 1rem;
        display: flex; gap: 0.8rem; align-items: center; flex-wrap: wrap;
    }
    .filters-bar input, .filters-bar select {
        border-radius: 10px; border: 1px solid #e2e8f0; padding: 0.5rem 0.9rem; font-size: 0.9rem;
    }
    .filters-bar input { flex: 1; min-width: 200px; }
    .btn-filter { background: #0F6E56; color: white; border: none; border-radius: 10px; padding: 0.5rem 1.2rem; font-weight: 600; font-size: 0.9rem; }
    .btn-reset { color: #64748b; text-decoration: none; font-size: 0.85rem; }
    .results-count { color: #64748b; font-size: 0.85rem; margin-bottom: 0.8rem; }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="GET" action="{{ route('admin.stagiaires.index') }}" class="filters-bar">
    <input type="text" name="recherche" placeholder="Rechercher un nom, prénom, email..." value="{{ request('recherche') }}">
    <select name="statut">
        <option value="">Tous les statuts</option>
        @foreach(['En attente', 'En cours', 'Terminé', 'Prolongé', 'Archivé'] as $statut)
            <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}>{{ $statut }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-filter">Filtrer</button>
    @if(request('recherche') || request('statut'))
        <a href="{{ route('admin.stagiaires.index') }}" class="btn-reset">Réinitialiser</a>
    @endif
</form>

<div class="results-count">{{ $stagiaires->total() }} stagiaire(s) trouvé(s)</div>

<div class="table-card">
    <table class="table">
        <thead>
            <tr>
                <th>Stagiaire</th>
                <th>École / Filière</th>
                <th>Niveau</th>
                <th>Période</th>
                <th>Statut</th>
                <th>Score</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stagiaires as $s)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-sm">{{ substr($s->utilisateur->prenom ?? '?', 0, 1) }}{{ substr($s->utilisateur->nom ?? '?', 0, 1) }}</div>
                        <div>
                            <div style="font-weight:600; color:#1e293b;">{{ $s->utilisateur->prenom ?? '—' }} {{ $s->utilisateur->nom ?? '' }}</div>
                            <div class="text-muted" style="font-size:0.8rem;">{{ $s->utilisateur->email ?? '—' }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div>{{ $s->ecole ?: '—' }}</div>
                    <div class="text-muted" style="font-size:0.85rem;">{{ $s->filiere ?: '—' }}</div>
                </td>
                <td>{{ $s->niveau_etude ?: '—' }}</td>
                <td style="font-size:0.85rem;">
                    {{ $s->date_debut ? \Carbon\Carbon::parse($s->date_debut)->format('d/m/Y') : '—' }}
                    →
                    {{ $s->date_fin ? \Carbon\Carbon::parse($s->date_fin)->format('d/m/Y') : '—' }}
                </td>
                <td>
                    <span class="statut-pill statut-{{ str_replace(' ', '_', $s->statut_calcule) }}">{{ $s->statut_calcule }}</span>
                </td>
                <td>
                    <span class="score-badge">{{ $s->score_global }}/100</span>
                </td>
                <td>
                    <a href="{{ route('admin.stagiaires.edit', $s->id_stagiaire) }}" class="btn-icon-action" title="Modifier">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted py-4">Aucun stagiaire trouvé.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $stagiaires->links() }}
</div>
@endsection