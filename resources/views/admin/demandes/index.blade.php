@extends('layouts.dashboard')

@section('title', 'Demandes - STAGE-UP')
@section('page-title', 'Gestion des demandes')
@section('page-subtitle', 'Consultez les demandes soumises par les stagiaires')

@section('tabs')
    <a href="/admin/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/admin/utilisateurs"><i class="bi bi-people-fill"></i> Utilisateurs</a>
    <a href="/admin/stagiaires"><i class="bi bi-mortarboard-fill"></i> Stagiaires</a>
    <a href="/admin/demandes" class="active"><i class="bi bi-file-earmark-text-fill"></i> Demandes @if($demandesEnAttente > 0)<span class="badge-count">{{ $demandesEnAttente }}</span>@endif</a>
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
    .type-pill { font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.7rem; border-radius: 20px; background: #eef2ff; color: #4f46e5; display: inline-block; }
    .statut-pill { font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.7rem; border-radius: 20px; display: inline-block; }
    .statut-En_attente { background: #fffbeb; color: #d97706; }
    .statut-Validée { background: #eafaf5; color: #0F6E56; }
    .statut-Rejetée { background: #fef2f2; color: #dc2626; }
    .statut-Traitée { background: #eef2ff; color: #4f46e5; }
    .btn-icon-action { border: none; background: none; color: #64748b; padding: 0.3rem 0.5rem; border-radius: 6px; text-decoration: none; }
    .btn-icon-action:hover { background: #f1f5f9; color: #1e293b; }

    .filters-bar {
        background: white; border-radius: 16px; border: 1px solid #eef0f4;
        padding: 1rem 1.2rem; margin-bottom: 1rem;
        display: flex; gap: 0.8rem; align-items: center; flex-wrap: wrap;
    }
    .filters-bar select { border-radius: 10px; border: 1px solid #e2e8f0; padding: 0.5rem 0.9rem; font-size: 0.9rem; }
    .btn-filter { background: #0F6E56; color: white; border: none; border-radius: 10px; padding: 0.5rem 1.2rem; font-weight: 600; font-size: 0.9rem; }
    .btn-reset { color: #64748b; text-decoration: none; font-size: 0.85rem; }
    .results-count { color: #64748b; font-size: 0.85rem; margin-bottom: 0.8rem; }
</style>

<form method="GET" action="{{ route('admin.demandes.index') }}" class="filters-bar">
    <select name="type">
        <option value="">Tous les types</option>
        @foreach(['Attestation', 'Prolongation', 'Absence', 'Autre'] as $type)
            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
        @endforeach
    </select>
    <select name="statut">
        <option value="">Tous les statuts</option>
        @foreach(['En attente', 'Validée', 'Rejetée', 'Traitée'] as $statut)
            <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}>{{ $statut }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-filter">Filtrer</button>
    @if(request('type') || request('statut'))
        <a href="{{ route('admin.demandes.index') }}" class="btn-reset">Réinitialiser</a>
    @endif
</form>

<div class="results-count">{{ $demandes->total() }} demande(s) trouvée(s)</div>

<div class="table-card">
    <table class="table">
        <thead>
            <tr>
                <th>Stagiaire</th>
                <th>Type</th>
                <th>Objet</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($demandes as $d)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-sm">{{ substr($d->stagiaire->utilisateur->prenom ?? '?', 0, 1) }}{{ substr($d->stagiaire->utilisateur->nom ?? '?', 0, 1) }}</div>
                        <div style="font-weight:600; color:#1e293b;">{{ $d->stagiaire->utilisateur->prenom ?? '—' }} {{ $d->stagiaire->utilisateur->nom ?? '' }}</div>
                    </div>
                </td>
                <td><span class="type-pill">{{ $d->type_demande }}</span></td>
                <td>{{ $d->objet }}</td>
                <td style="font-size:0.85rem;">{{ \Carbon\Carbon::parse($d->date_demande)->format('d/m/Y') }}</td>
                <td>
                    <span class="statut-pill statut-{{ str_replace(' ', '_', $d->statut) }}">{{ $d->statut }}</span>
                </td>
                <td>
                    <a href="{{ route('admin.demandes.show', $d->id_demande) }}" class="btn-icon-action" title="Voir">
                        <i class="bi bi-eye-fill"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted py-4">Aucune demande pour l'instant.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $demandes->links() }}
</div>
@endsection