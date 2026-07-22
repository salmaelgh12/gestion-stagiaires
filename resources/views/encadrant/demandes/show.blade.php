@extends('layouts.dashboard')

@section('title', 'Détail demande - STAGE-UP')
@section('page-title', 'Détail de la demande')
@section('page-subtitle', $demande->objet)

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
.detail-wrapper { display: flex; justify-content: center; }
.detail-card { background: white; border-radius: 16px; padding: 2rem; border: 1px solid #eef0f4; max-width: 650px; width: 100%; }
.detail-row { display: flex; justify-content: space-between; padding: 0.8rem 0; border-bottom: 1px solid #f1f5f9; }
.detail-row .label { color: #64748b; font-size: 0.9rem; }
.detail-row .value { color: #1e293b; font-weight: 600; }
.btn-back { color: #64748b; text-decoration: none; font-size: 0.9rem; display: inline-block; margin-bottom: 1rem; }
.action-buttons { display: flex; gap: 0.8rem; margin-top: 1.5rem; }
.btn-valider { background: #0F6E56; color: white; border: none; border-radius: 10px; padding: 0.7rem 1.5rem; font-weight: 600; flex: 1; }
.btn-rejeter { background: #fef2f2; color: #dc2626; border: none; border-radius: 10px; padding: 0.7rem 1.5rem; font-weight: 600; flex: 1; }
textarea.form-control { border-radius: 10px; border: 1px solid #e2e8f0; padding: 0.6rem 1rem; width: 100%; margin-top: 1rem; }
.statut-badge { display: inline-block; margin-top: 1.5rem; padding: 0.6rem 1.2rem; border-radius: 10px; font-weight: 700; font-size: 0.9rem; }
.statut-validee { background: #eafaf5; color: #0F6E56; }
.statut-rejetee { background: #fef2f2; color: #dc2626; }
</style>

<a href="{{ route('encadrant.demandes.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Retour</a>

<div class="detail-wrapper">
<div class="detail-card">
    <div class="detail-row"><span class="label">Stagiaire</span><span class="value">{{ $demande->stagiaire->utilisateur->prenom ?? '—' }} {{ $demande->stagiaire->utilisateur->nom ?? '' }}</span></div>
    <div class="detail-row"><span class="label">Type</span><span class="value">{{ $demande->type_demande }}</span></div>
    <div class="detail-row"><span class="label">Objet</span><span class="value">{{ $demande->objet }}</span></div>
    <div class="detail-row"><span class="label">Description</span><span class="value">{{ $demande->description ?: '—' }}</span></div>
    <div class="detail-row"><span class="label">Date</span><span class="value">{{ \Carbon\Carbon::parse($demande->date_demande)->format('d/m/Y') }}</span></div>

    @if($maValidation)
        <div class="statut-badge {{ $maValidation->decision === 'Validée' ? 'statut-validee' : 'statut-rejetee' }}">
            <i class="bi {{ $maValidation->decision === 'Validée' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
            Vous avez {{ $maValidation->decision === 'Validée' ? 'validé' : 'rejeté' }} cette demande
            le {{ \Carbon\Carbon::parse($maValidation->date_validation)->format('d/m/Y à H:i') }}
            @if($maValidation->commentaire)
                <div style="font-weight: 400; margin-top: 0.4rem; font-size: 0.85rem;">« {{ $maValidation->commentaire }} »</div>
            @endif
        </div>
    @else
        <form method="POST" action="{{ route('encadrant.demandes.valider', $demande->id_demande) }}">
            @csrf
            <textarea name="commentaire" class="form-control" placeholder="Commentaire (optionnel)" rows="2"></textarea>
            <div class="action-buttons">
                <button type="submit" class="btn-valider">Valider</button>
            </div>
        </form>
        <form method="POST" action="{{ route('encadrant.demandes.rejeter', $demande->id_demande) }}" class="mt-2">
            @csrf
            <button type="submit" class="btn-rejeter w-100">Rejeter</button>
        </form>
    @endif
</div>
</div>
@endsection