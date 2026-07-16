@extends('layouts.dashboard')

@section('title', 'Mes stagiaires - STAGE-UP')
@section('page-title', 'Mes stagiaires')
@section('page-subtitle', 'Stagiaires actuellement sous votre encadrement')

@section('tabs')
    <a href="/encadrant/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/encadrant/stagiaires" class="active"><i class="bi bi-mortarboard-fill"></i> Mes stagiaires</a>
    <a href="/encadrant/taches"><i class="bi bi-list-task"></i> Tâches</a>
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
</style>

<div class="table-card">
    <table class="table">
        <thead>
            <tr>
                <th>Stagiaire</th>
                <th>École / Filière</th>
                <th>Stage</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
            @forelse($affectations as $a)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-sm">{{ substr($a->stagiaire->utilisateur->prenom ?? '?', 0, 1) }}{{ substr($a->stagiaire->utilisateur->nom ?? '?', 0, 1) }}</div>
                        <div>
                            <div style="font-weight:600; color:#1e293b;">{{ $a->stagiaire->utilisateur->prenom ?? '—' }} {{ $a->stagiaire->utilisateur->nom ?? '' }}</div>
                            <div class="text-muted" style="font-size:0.8rem;">{{ $a->stagiaire->utilisateur->email ?? '—' }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div>{{ $a->stagiaire->ecole ?? '—' }}</div>
                    <div class="text-muted" style="font-size:0.85rem;">{{ $a->stagiaire->filiere ?? '—' }}</div>
                </td>
                <td>{{ $a->stage->titre ?? '—' }}</td>
                <td><strong>{{ $a->stagiaire->score_global ?? 0 }}/100</strong></td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-muted py-4">Aucun stagiaire ne vous est actuellement affecté.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection