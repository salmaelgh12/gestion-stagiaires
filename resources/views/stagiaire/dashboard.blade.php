@extends('layouts.dashboard')

@section('title', 'Mon espace - STAGE-UP')
@section('page-title', 'Mon espace stagiaire')
@section('page-subtitle', 'Suivez votre parcours de stage')

@section('tabs')
    <a href="/stagiaire/dashboard" class="active"><i class="bi bi-bar-chart-fill"></i> Vue d'ensemble</a>
    <a href="/stagiaire/demandes"><i class="bi bi-file-earmark-text-fill"></i> Mes demandes</a>
    <a href="/stagiaire/absences"><i class="bi bi-calendar-x"></i> Mes absences</a>
@endsection

@section('content')
<style>
.stat-card { background: white; border-radius: 12px; padding: 1rem 1.2rem; border: 1px solid #eef0f4; display: flex; align-items: center; gap: 0.8rem; }
.stat-card .icon { width: 34px; height: 34px; min-width: 34px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; }
.stat-card .val { font-size: 1.15rem; font-weight: 700; color: #1e293b; line-height: 1.1; }
.stat-card .label { color: #94a3b8; font-size: 0.72rem; margin-top: 1px; }
.info-card { background: white; border-radius: 12px; padding: 1.2rem; border: 1px solid #eef0f4; margin-top: 1rem; }
.info-row { display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9; font-size: 0.85rem; }
.info-row:last-child { border-bottom: none; }
.info-row .label { color: #64748b; }
.info-row .value { color: #1e293b; font-weight: 600; }
.section-label { font-size: 0.72rem; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; margin-bottom: 0.8rem; }
</style>

@if(!$stagiaire)
<div class="alert alert-warning">Aucune information de stage associée à votre compte pour le moment. Contactez votre administrateur.</div>
@else
<div class="row g-3">
<div class="col-md-4">
<div class="stat-card">
<div class="icon" style="background:#eafaf5; color:#0F6E56;"><i class="bi bi-graph-up"></i></div>
<div>
<div class="val">{{ $stagiaire->score_global }}/100</div>
<div class="label">Score global</div>
</div>
</div>
</div>
<div class="col-md-4">
<div class="stat-card">
<div class="icon" style="background:#eef2ff; color:#4f46e5;"><i class="bi bi-flag-fill"></i></div>
<div>
<div class="val">{{ $stagiaire->statut_calcule }}</div>
<div class="label">Statut du stage</div>
</div>
</div>
</div>
<div class="col-md-4">
<div class="stat-card">
<div class="icon" style="background:#fffbeb; color:#d97706;"><i class="bi bi-calendar-check"></i></div>
<div>
<div class="val">{{ $joursRestants }} j.</div>
<div class="label">Jours restants</div>
</div>
</div>
</div>
</div>

<div class="info-card">
<div class="section-label">INFORMATIONS DU STAGE</div>
<div class="info-row"><span class="label">École</span><span class="value">{{ $stagiaire->ecole ?: '—' }}</span></div>
<div class="info-row"><span class="label">Filière</span><span class="value">{{ $stagiaire->filiere ?: '—' }}</span></div>
<div class="info-row"><span class="label">Niveau d'étude</span><span class="value">{{ $stagiaire->niveau_etude ?: '—' }}</span></div>
<div class="info-row"><span class="label">Date de début</span><span class="value">{{ $stagiaire->date_debut ? \Carbon\Carbon::parse($stagiaire->date_debut)->format('d/m/Y') : '—' }}</span></div>
<div class="info-row"><span class="label">Date de fin</span><span class="value">{{ $stagiaire->date_fin ? \Carbon\Carbon::parse($stagiaire->date_fin)->format('d/m/Y') : '—' }}</span></div>
</div>
@endif
@endsection