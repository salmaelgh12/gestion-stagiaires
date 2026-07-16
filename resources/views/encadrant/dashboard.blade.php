@extends('layouts.dashboard')

@section('title', 'Mon espace - STAGE-UP')
@section('page-title', 'Espace Encadrant')
@section('page-subtitle', 'Suivez vos stagiaires et leurs tâches')

@section('tabs')
    <a href="/encadrant/dashboard" class="active"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/encadrant/stagiaires"><i class="bi bi-mortarboard-fill"></i> Mes stagiaires</a>
    <a href="/encadrant/taches"><i class="bi bi-list-task"></i> Tâches</a>
@endsection

@section('content')
<style>
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid #eef0f4;
    }
    .stat-card .val { font-size: 1.8rem; font-weight: 800; color: #1e293b; }
    .stat-card .label { color: #64748b; font-size: 0.85rem; }
    .stat-card .icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        margin-bottom: 0.8rem;
    }
</style>

<div class="row g-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon" style="background:#eef2ff; color:#4f46e5;"><i class="bi bi-mortarboard-fill"></i></div>
            <div class="val">{{ $mesStagiaires }}</div>
            <div class="label">Mes stagiaires</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon" style="background:#eafaf5; color:#0F6E56;"><i class="bi bi-hourglass-split"></i></div>
            <div class="val">{{ $tachesEnCours }}</div>
            <div class="label">Tâches en cours</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon" style="background:#fef2f2; color:#dc2626;"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div class="val">{{ $tachesEnRetard }}</div>
            <div class="label">Tâches en retard</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon" style="background:#fdf2f8; color:#ec4899;"><i class="bi bi-check-circle-fill"></i></div>
            <div class="val">{{ $tachesTerminees }}</div>
            <div class="label">Tâches terminées</div>
        </div>
    </div>
</div>
@endsection