@extends('layouts.dashboard')

@section('title', 'Dashboard Admin - STAGE-UP')
@section('page-title', 'Tableau de bord')

@section('sidebar-links')
    <a href="/admin/dashboard" class="active">📊 Tableau de bord</a>
    <a href="#">👥 Utilisateurs</a>
    <a href="#">🎓 Stagiaires</a>
    <a href="#">📋 Demandes</a>
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
            <div class="icon" style="background:#eafaf5; color:#0F6E56;">👥</div>
            <div class="val">{{ $totalUtilisateurs }}</div>
            <div class="label">Utilisateurs total</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon" style="background:#eef2ff; color:#4f46e5;">🎓</div>
            <div class="val">{{ $totalStagiaires }}</div>
            <div class="label">Stagiaires total</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon" style="background:#fdf2f8; color:#ec4899;">✅</div>
            <div class="val">{{ $stagiairesActifs }}</div>
            <div class="label">Stagiaires actifs</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon" style="background:#fffbeb; color:#d97706;">⏳</div>
            <div class="val">{{ $demandesEnAttente }}</div>
            <div class="label">Demandes en attente</div>
        </div>
    </div>
</div>
@endsection