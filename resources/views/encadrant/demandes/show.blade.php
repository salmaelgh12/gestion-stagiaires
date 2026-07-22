@extends('layouts.dashboard')

@section('title', 'Détail demande - STAGE-UP')
@section('page-title', 'Détail de la demande')
@section('page-subtitle', $demande->objet)

@section('tabs')
    <a href="/encadrant/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/encadrant/stagiaires"><i class="bi bi-mortarboard-fill"></i> Mes stagiaires</a>
    <a href="/encadrant/taches"><i class="bi bi-list-task"></i> Tâches</a>
    <a href="/encadrant/absences"><i class="bi bi-calendar-x"></i> Absences</a>
    <a href="/encadrant/demandes" class="active"><i class="bi bi-file-earmark-text-fill"></i> Demandes</a>
@endsection

@section('content')
<style>
.detail-card { background: white; border-radius: 16px; padding: 2rem; border: 1px solid #eef0f4; max-width: 650px; }
.detail-row { display: flex; justify-content: space-between; padding: 0.8rem 0; border-bottom: 1px solid #f1f5f9; }
.detail-row .label { color: #64748b; font-size: 0.9rem; }
.detail-row .value { color: #1e293b; font-weight: 600; }
.btn-back { color: #64748b; text-decoration: none; font-size: 0.9rem; display: inline-block; margin-bottom: 1rem; }
.action-buttons { display: flex; gap: 0.8rem; margin-top: 1.5rem; }
.btn-valider { background: #0F6E56; color: white; border: none; border-radius: 10px; padding: 0.7rem 1.5rem; font-weight: 600; flex: 1; }
.btn-rejeter { background: #fef2f2; color: #dc2626; border: none; border-radius: 10px; padding: 0.7rem 1.5rem; font-weight: 600; flex: 1; }
textarea.form-control { border-radius: 10px; border: 1px solid #e2e8f0; padding: 0.6rem 1rem; width: 100%; margin-top: 1rem; }
</style>

<a href="{{ route('encadrant.demandes.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Retour</a>

<div class="detail-card">
    <div class="detail-row"><span class="label">Stagiaire</span><span class="value">{{ $demande->stagiaire->utilisateur->prenom ?? '—' }} {{ $demande->stagiaire->utilisateur->nom ?? '' }}</span></div>
    <div class="detail-row"><span class="label">Type</span><span class="value">{{ $demande->type_demande }}</span></div>
    <div class="detail-row"><span class="label">Objet</span><span class="value">{{ $demande->objet }}</span></div>
    <div class="detail-row"><span class="label">Description</span><span class="value">{{ $demande->description ?: '—' }}</span></div>
    <div class="detail-row"><span class="label">Date</span><span class="value">{{ \Carbon\Carbon::parse($demande->date_demande)->format('d/m/Y') }}</span></div>

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
</div>
@endsection