@extends('layouts.dashboard')

@section('title', 'Stages - STAGE-UP')
@section('page-title', 'Gestion des stages')
@section('page-subtitle', 'Créez et gérez les offres de stage')

@section('tabs')
    <a href="/responsable/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/responsable/stagiaires"><i class="bi bi-mortarboard-fill"></i> Stagiaires</a>
    <a href="/responsable/stages" class="active"><i class="bi bi-briefcase-fill"></i> Stages</a>
    <a href="/responsable/affectations"><i class="bi bi-link-45deg"></i> Affectations</a>
    <a href="/responsable/demandes"><i class="bi bi-file-earmark-text-fill"></i> Demandes</a>
@endsection

@section('content')
<style>
.table-card { background: white; border-radius: 16px; border: 1px solid #eef0f4; overflow: hidden; }
.table-card table { margin: 0; }
.table-card th { background: #f8fafc; font-size: 0.8rem; color: #64748b; font-weight: 700; border-bottom: 1px solid #eef0f4; padding: 1rem; }
.table-card td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
.statut-pill { font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.7rem; border-radius: 20px; display: inline-block; }
.statut-Ouvert { background: #eafaf5; color: #0F6E56; }
.statut-Pourvu { background: #eef2ff; color: #4f46e5; }
.statut-Clos { background: #f1f5f9; color: #64748b; }
.btn-add { background: #0F6E56; color: white; border: none; border-radius: 10px; padding: 0.6rem 1.3rem; font-weight: 600; text-decoration: none; font-size: 0.9rem; }
.btn-add:hover { background: #0c5a46; color: white; }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('responsable.stages.create') }}" class="btn-add"><i class="bi bi-plus-lg"></i> Nouveau stage</a>
</div>

<div class="table-card">
    <table class="table">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Département</th>
                <th>Période</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stages as $s)
            <tr>
                <td style="font-weight:600; color:#1e293b;">{{ $s->titre }}</td>
                <td>{{ $s->departement ?: '—' }}</td>
                <td style="font-size:0.85rem;">
                    {{ \Carbon\Carbon::parse($s->date_debut)->format('d/m/Y') }} → {{ \Carbon\Carbon::parse($s->date_fin)->format('d/m/Y') }}
                </td>
                <td><span class="statut-pill statut-{{ $s->statut }}">{{ $s->statut }}</span></td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center text-muted py-4">Aucun stage créé.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $stages->links() }}</div>
@endsection