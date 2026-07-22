@extends('layouts.dashboard')

@section('title', 'Affectations - STAGE-UP')
@section('page-title', 'Affectations')
@section('page-subtitle', 'Liens entre stagiaires, stages et encadrants')

@section('tabs')
    <a href="/responsable/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/responsable/stagiaires"><i class="bi bi-mortarboard-fill"></i> Stagiaires</a>
    <a href="/responsable/stages"><i class="bi bi-briefcase-fill"></i> Stages</a>
    <a href="/responsable/affectations" class="active"><i class="bi bi-link-45deg"></i> Affectations</a>
    <a href="/responsable/demandes"><i class="bi bi-file-earmark-text-fill"></i> Demandes</a>
@endsection

@section('content')
<style>
.table-card { background: white; border-radius: 16px; border: 1px solid #eef0f4; overflow: hidden; }
.table-card table { margin: 0; }
.table-card th { background: #f8fafc; font-size: 0.8rem; color: #64748b; font-weight: 700; border-bottom: 1px solid #eef0f4; padding: 1rem; }
.table-card td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
.btn-add { background: #0F6E56; color: white; border: none; border-radius: 10px; padding: 0.6rem 1.3rem; font-weight: 600; text-decoration: none; font-size: 0.9rem; }
.btn-add:hover { background: #0c5a46; color: white; }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('responsable.affectations.create') }}" class="btn-add"><i class="bi bi-plus-lg"></i> Nouvelle affectation</a>
</div>

<div class="table-card">
    <table class="table">
        <thead>
            <tr>
                <th>Stagiaire</th>
                <th>Stage</th>
                <th>Encadrant</th>
                <th>Date d'affectation</th>
            </tr>
        </thead>
        <tbody>
            @forelse($affectations as $a)
            <tr>
                <td style="font-weight:600; color:#1e293b;">{{ $a->stagiaire->utilisateur->prenom ?? '—' }} {{ $a->stagiaire->utilisateur->nom ?? '' }}</td>
                <td>{{ $a->stage->titre ?? '—' }}</td>
                <td>{{ $a->encadrant->prenom ?? '—' }} {{ $a->encadrant->nom ?? '' }}</td>
                <td style="font-size:0.85rem;">{{ \Carbon\Carbon::parse($a->date_affectation)->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center text-muted py-4">Aucune affectation créée.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $affectations->links() }}</div>
@endsection