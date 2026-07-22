@extends('layouts.dashboard')

@section('title', 'Stagiaires - STAGE-UP')
@section('page-title', 'Liste des stagiaires')
@section('page-subtitle', 'Vue globale de tous les stagiaires')

@section('tabs')
    <a href="/responsable/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/responsable/stagiaires" class="active"><i class="bi bi-mortarboard-fill"></i> Stagiaires</a>
    <a href="/responsable/stages"><i class="bi bi-briefcase-fill"></i> Stages</a>
    <a href="/responsable/affectations"><i class="bi bi-link-45deg"></i> Affectations</a>
    <a href="/responsable/demandes"><i class="bi bi-file-earmark-text-fill"></i> Demandes</a>
@endsection

@section('content')
<style>
.table-card { background: white; border-radius: 16px; border: 1px solid #eef0f4; overflow: hidden; }
.table-card table { margin: 0; }
.table-card th { background: #f8fafc; font-size: 0.8rem; color: #64748b; font-weight: 700; border-bottom: 1px solid #eef0f4; padding: 1rem; }
.table-card td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
.avatar-sm { width: 36px; height: 36px; border-radius: 50%; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; }
.statut-pill { font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.7rem; border-radius: 20px; display: inline-block; }
.statut-En_attente { background: #fffbeb; color: #d97706; }
.statut-En_cours { background: #eafaf5; color: #0F6E56; }
.statut-Terminé { background: #eef2ff; color: #4f46e5; }
.statut-Prolongé { background: #fdf2f8; color: #ec4899; }
.statut-Archivé { background: #f1f5f9; color: #64748b; }
.filters-bar { background: white; border-radius: 16px; border: 1px solid #eef0f4; padding: 1rem 1.2rem; margin-bottom: 1rem; display: flex; gap: 0.8rem; align-items: center; flex-wrap: wrap; }
.filters-bar input, .filters-bar select { border-radius: 10px; border: 1px solid #e2e8f0; padding: 0.5rem 0.9rem; font-size: 0.9rem; }
.filters-bar input { flex: 1; min-width: 200px; }
.btn-filter { background: #0F6E56; color: white; border: none; border-radius: 10px; padding: 0.5rem 1.2rem; font-weight: 600; font-size: 0.9rem; }
.btn-add { background: #0F6E56; color: white; border: none; border-radius: 10px; padding: 0.6rem 1.3rem; font-weight: 600; text-decoration: none; font-size: 0.9rem; }
.btn-add:hover { background: #0c5a46; color: white; }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <form method="GET" action="{{ route('responsable.stagiaires.index') }}" class="filters-bar mb-0">
        <input type="text" name="recherche" placeholder="Rechercher..." value="{{ request('recherche') }}">
        <select name="statut">
            <option value="">Tous les statuts</option>
            @foreach(['En attente', 'En cours', 'Terminé', 'Prolongé', 'Archivé'] as $statut)
                <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}>{{ $statut }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-filter">Filtrer</button>
    </form>

    <a href="{{ route('responsable.stagiaires.create') }}" class="btn-add"><i class="bi bi-plus-lg"></i> Ajouter un stagiaire</a>
</div>

<div class="table-card">
    <table class="table">
        <thead>
            <tr>
                <th>Stagiaire</th>
                <th>École / Filière</th>
                <th>Statut</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stagiaires as $s)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-sm">{{ substr($s->utilisateur->prenom ?? '?', 0, 1) }}{{ substr($s->utilisateur->nom ?? '?', 0, 1) }}</div>
                        <div style="font-weight:600; color:#1e293b;">{{ $s->utilisateur->prenom ?? '—' }} {{ $s->utilisateur->nom ?? '' }}</div>
                    </div>
                </td>
                <td>{{ $s->ecole ?: '—' }} / {{ $s->filiere ?: '—' }}</td>
                <td><span class="statut-pill statut-{{ str_replace(' ', '_', $s->statut_calcule) }}">{{ $s->statut_calcule }}</span></td>
                <td><strong>{{ $s->score_global }}/100</strong></td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center text-muted py-4">Aucun stagiaire trouvé.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $stagiaires->links() }}</div>
@endsection