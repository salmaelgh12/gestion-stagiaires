@extends('layouts.dashboard')

@section('title', 'Demandes - STAGE-UP')
@section('page-title', 'Demandes à valider')
@section('page-subtitle', 'Demandes soumises par vos stagiaires')

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
    <a href="/encadrant/taches"><i class="bi bi-list-task"></i> Tâches @if($tachesEnRetardCount > 0)<span class="badge-count">{{ $tachesEnRetardCount > 9 ? '9+' : $tachesEnRetardCount }}</span>@endif</a>
    <a href="/encadrant/absences"><i class="bi bi-calendar-x"></i> Absences @if($absencesNonTraiteesCount > 0)<span class="badge-count">{{ $absencesNonTraiteesCount > 9 ? '9+' : $absencesNonTraiteesCount }}</span>@endif</a>
    <a href="/encadrant/demandes" class="active"><i class="bi bi-file-earmark-text-fill"></i> Demandes @if($demandesEnAttenteCount > 0)<span class="badge-count">{{ $demandesEnAttenteCount > 9 ? '9+' : $demandesEnAttenteCount }}</span>@endif</a>
@endsection

@section('content')
<style>
.table-card { background: white; border-radius: 16px; border: 1px solid #eef0f4; overflow: hidden; }
.table-card table { margin: 0; }
.table-card th { background: #f8fafc; font-size: 0.8rem; color: #64748b; font-weight: 700; border-bottom: 1px solid #eef0f4; padding: 1rem; }
.table-card td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
.type-pill { font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.7rem; border-radius: 20px; background: #eef2ff; color: #4f46e5; display: inline-block; }
.btn-icon-action { border: none; background: none; color: #64748b; padding: 0.3rem 0.5rem; border-radius: 6px; text-decoration: none; }
.btn-icon-action:hover { background: #f1f5f9; }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-card">
    <table class="table">
        <thead>
            <tr>
                <th>Stagiaire</th>
                <th>Type</th>
                <th>Objet</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($demandes as $d)
            <tr>
                <td style="font-weight:600; color:#1e293b;">{{ $d->stagiaire->utilisateur->prenom ?? '—' }} {{ $d->stagiaire->utilisateur->nom ?? '' }}</td>
                <td><span class="type-pill">{{ $d->type_demande }}</span></td>
                <td>{{ $d->objet }}</td>
                <td style="font-size:0.85rem;">{{ \Carbon\Carbon::parse($d->date_demande)->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('encadrant.demandes.show', $d->id_demande) }}" class="btn-icon-action" title="Voir">
                        <i class="bi bi-eye-fill"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted py-4">Aucune demande en attente de validation.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $demandes->links() }}</div>
@endsection