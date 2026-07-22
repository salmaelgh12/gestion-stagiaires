@extends('layouts.dashboard')

@section('title', 'Tâches - STAGE-UP')
@section('page-title', 'Gestion des tâches')
@section('page-subtitle', 'Assignez et suivez les tâches de vos stagiaires')

@php
    $mesStagiairesIdsNav = \App\Models\AffectationStage::where('id_encadrant', Auth::id())->where('active', true)->pluck('id_stagiaire');
    $demandesEnAttenteCount = \App\Models\Demande::whereIn('id_stagiaire', $mesStagiairesIdsNav)
        ->whereDoesntHave('validations', fn($q) => $q->where('role_validateur', 'Encadrant'))->count();
    $tachesEnRetardCount = \App\Models\Tache::where('id_encadrant', Auth::id())
        ->where('date_echeance', '<', now())->where('statut', '!=', 'Terminée')->count();
    $absencesNonTraiteesCount = \App\Models\Absence::whereIn('id_stagiaire', $mesStagiairesIdsNav)
        ->whereNull('validee_par')->count();
@endphp
@section('tabs')
    <a href="/encadrant/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/encadrant/stagiaires"><i class="bi bi-mortarboard-fill"></i> Mes stagiaires</a>
    <a href="/encadrant/taches" class="active"><i class="bi bi-list-task"></i> Tâches @if($tachesEnRetardCount > 0)<span class="badge-count">{{ $tachesEnRetardCount > 9 ? '9+' : $tachesEnRetardCount }}</span>@endif</a>
    <a href="/encadrant/absences"><i class="bi bi-calendar-x"></i> Absences @if($absencesNonTraiteesCount > 0)<span class="badge-count">{{ $absencesNonTraiteesCount > 9 ? '9+' : $absencesNonTraiteesCount }}</span>@endif</a>
    <a href="/encadrant/demandes"><i class="bi bi-file-earmark-text-fill"></i> Demandes @if($demandesEnAttenteCount > 0)<span class="badge-count">{{ $demandesEnAttenteCount > 9 ? '9+' : $demandesEnAttenteCount }}</span>@endif</a>
@endsection

@section('content')
<style>
    .table-card { background: white; border-radius: 16px; border: 1px solid #eef0f4; overflow: hidden; }
    .table-card table { margin: 0; }
    .table-card th { background: #f8fafc; font-size: 0.8rem; color: #64748b; font-weight: 700; border-bottom: 1px solid #eef0f4; padding: 1rem; }
    .table-card td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
    .priorite-pill { font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.7rem; border-radius: 20px; display: inline-block; }
    .priorite-Faible { background: #f1f5f9; color: #64748b; }
    .priorite-Moyenne { background: #fffbeb; color: #d97706; }
    .priorite-Haute { background: #fef2f2; color: #dc2626; }
    .statut-pill { font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.7rem; border-radius: 20px; display: inline-block; }
    .statut-A_faire { background: #f1f5f9; color: #64748b; }
    .statut-En_cours { background: #eafaf5; color: #0F6E56; }
    .statut-Terminée { background: #eef2ff; color: #4f46e5; }
    .statut-Annulée { background: #fef2f2; color: #dc2626; }
    .progress-bar-custom { height: 6px; border-radius: 10px; background: #e2e8f0; overflow: hidden; width: 80px; }
    .progress-bar-custom .fill { height: 100%; background: #0F6E56; }
    .btn-add {
        background: #0F6E56; color: white; border: none;
        border-radius: 10px; padding: 0.6rem 1.3rem; font-weight: 600;
        text-decoration: none; font-size: 0.9rem;
    }
    .btn-add:hover { background: #0c5a46; color: white; }
    .btn-icon-action {
        border: none; background: none; color: #64748b;
        padding: 0.3rem 0.5rem; border-radius: 6px; text-decoration: none;
    }
    .btn-icon-action:hover { background: #f1f5f9; }

    .filters-bar {
        background: white; border-radius: 16px; border: 1px solid #eef0f4;
        padding: 1rem 1.2rem; margin-bottom: 1rem;
        display: flex; gap: 0.8rem; align-items: center; flex-wrap: wrap;
    }
    .filters-bar select { border-radius: 10px; border: 1px solid #e2e8f0; padding: 0.5rem 0.9rem; font-size: 0.9rem; }
    .btn-filter { background: #0F6E56; color: white; border: none; border-radius: 10px; padding: 0.5rem 1.2rem; font-weight: 600; font-size: 0.9rem; }
    .btn-reset { color: #64748b; text-decoration: none; font-size: 0.85rem; }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <form method="GET" action="{{ route('encadrant.taches.index') }}" class="filters-bar mb-0">
        <select name="stagiaire">
            <option value="">Tous les stagiaires</option>
            @foreach($mesStagiairesListe as $s)
                <option value="{{ $s->id_stagiaire }}" {{ request('stagiaire') == $s->id_stagiaire ? 'selected' : '' }}>
                    {{ $s->utilisateur->prenom ?? '' }} {{ $s->utilisateur->nom ?? '' }}
                </option>
            @endforeach
        </select>
        <select name="statut">
            <option value="">Tous les statuts</option>
            @foreach(['A faire', 'En cours', 'Terminée', 'Annulée'] as $statut)
                <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}>{{ $statut }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-filter">Filtrer</button>
        @if(request('statut') || request('stagiaire'))
            <a href="{{ route('encadrant.taches.index') }}" class="btn-reset">Réinitialiser</a>
        @endif
    </form>

    <a href="{{ route('encadrant.taches.create') }}" class="btn-add">
        <i class="bi bi-plus-lg"></i> Nouvelle tâche
    </a>
</div>

<div class="table-card">
    <table class="table">
        <thead>
            <tr>
                <th>Tâche</th>
                <th>Stagiaire</th>
                <th>Échéance</th>
                <th>Priorité</th>
                <th>Statut</th>
                <th>Avancement</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($taches as $t)
            <tr>
                <td style="font-weight:600; color:#1e293b;">{{ $t->titre }}</td>
                <td>{{ $t->stagiaire->utilisateur->prenom ?? '—' }} {{ $t->stagiaire->utilisateur->nom ?? '' }}</td>
                <td style="font-size:0.85rem;">{{ \Carbon\Carbon::parse($t->date_echeance)->format('d/m/Y') }}</td>
                <td><span class="priorite-pill priorite-{{ $t->priorite }}">{{ $t->priorite }}</span></td>
                <td><span class="statut-pill statut-{{ str_replace(' ', '_', $t->statut) }}">{{ $t->statut }}</span></td>
                <td>
                    <div class="progress-bar-custom">
                        <div class="fill" style="width:{{ $t->pourcentage_avancement }}%;"></div>
                    </div>
                    <span style="font-size:0.75rem; color:#64748b;">{{ $t->pourcentage_avancement }}%</span>
                </td>
                <td>
                    <a href="{{ route('encadrant.taches.edit', $t->id_tache) }}" class="btn-icon-action" title="Modifier">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                    <form method="POST" action="{{ route('encadrant.taches.destroy', $t->id_tache) }}" class="d-inline" onsubmit="return confirm('Supprimer cette tâche ?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-icon-action text-danger" title="Supprimer">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted py-4">Aucune tâche pour l'instant.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $taches->links() }}
</div>
@endsection