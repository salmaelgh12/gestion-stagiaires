@extends('layouts.dashboard')

@section('title', 'Détail demande - STAGE-UP')
@section('page-title', 'Détail de la demande')
@section('page-subtitle', $demande->objet)

@section('tabs')
    <a href="/admin/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/admin/utilisateurs"><i class="bi bi-people-fill"></i> Utilisateurs</a>
    <a href="/admin/stagiaires"><i class="bi bi-mortarboard-fill"></i> Stagiaires</a>
    <a href="/admin/demandes" class="active"><i class="bi bi-file-earmark-text-fill"></i> Demandes</a>
@endsection

@section('content')
<style>
    .detail-card { background: white; border-radius: 16px; padding: 2rem; border: 1px solid #eef0f4; max-width: 700px; }
    .detail-row { display: flex; justify-content: space-between; padding: 0.8rem 0; border-bottom: 1px solid #f1f5f9; }
    .detail-row .label { color: #64748b; font-size: 0.9rem; }
    .detail-row .value { color: #1e293b; font-weight: 600; }
    .btn-back { color: #64748b; text-decoration: none; font-size: 0.9rem; display: inline-block; margin-bottom: 1rem; }
</style>

<a href="{{ route('admin.demandes.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Retour aux demandes</a>

<div class="detail-card">
    <div class="detail-row">
        <span class="label">Stagiaire</span>
        <span class="value">{{ $demande->stagiaire->utilisateur->prenom ?? '—' }} {{ $demande->stagiaire->utilisateur->nom ?? '' }}</span>
    </div>
    <div class="detail-row">
        <span class="label">Type de demande</span>
        <span class="value">{{ $demande->type_demande }}</span>
    </div>
    <div class="detail-row">
        <span class="label">Objet</span>
        <span class="value">{{ $demande->objet }}</span>
    </div>
    <div class="detail-row">
        <span class="label">Description</span>
        <span class="value">{{ $demande->description ?: '—' }}</span>
    </div>
    <div class="detail-row">
        <span class="label">Date de la demande</span>
        <span class="value">{{ \Carbon\Carbon::parse($demande->date_demande)->format('d/m/Y') }}</span>
    </div>
    <div class="detail-row">
        <span class="label">Statut</span>
        <span class="value">{{ $demande->statut }}</span>
    </div>
</div>
@endsection