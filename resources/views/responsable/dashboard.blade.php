@extends('layouts.dashboard')

@section('title', 'Mon espace - STAGE-UP')
@section('page-title', 'Espace Responsable de compétence')
@section('page-subtitle', 'Supervisez les stagiaires et validez les demandes')

@section('tabs')
    <a href="/responsable/dashboard" class="active"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/responsable/stagiaires"><i class="bi bi-mortarboard-fill"></i> Stagiaires</a>
    <a href="/responsable/demandes"><i class="bi bi-file-earmark-text-fill"></i> Demandes</a>
@endsection

@section('content')
<style>
.stat-card { background: white; border-radius: 16px; padding: 1.5rem; border: 1px solid #eef0f4; }
.stat-card .val { font-size: 1.8rem; font-weight: 800; color: #1e293b; }
.stat-card .label { color: #64748b; font-size: 0.85rem; }
.stat-card .icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-bottom: 0.8rem; }
</style>

<div class="row g-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon" style="background:#eef2ff; color:#4f46e5;"><i class="bi bi-mortarboard-fill"></i></div>
            <div class="val">{{ $totalStagiaires }}</div>
            <div class="label">Stagiaires total</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon" style="background:#eafaf5; color:#0F6E56;"><i class="bi bi-check-circle-fill"></i></div>
            <div class="val">{{ $stagiairesActifs }}</div>
            <div class="label">Stagiaires actifs</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon" style="background:#fffbeb; color:#d97706;"><i class="bi bi-hourglass-split"></i></div>
            <div class="val">{{ $demandesAValider }}</div>
            <div class="label">Demandes à valider</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon" style="background:#fdf2f8; color:#ec4899;"><i class="bi bi-clipboard-check-fill"></i></div>
            <div class="val">{{ $validationsFaites }}</div>
            <div class="label">Validations effectuées</div>
        </div>
    </div>
</div>
@endsection