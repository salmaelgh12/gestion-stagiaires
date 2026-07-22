@extends('layouts.dashboard')

@section('title', 'Demandes - STAGE-UP')
@section('page-title', 'Demandes à valider')
@section('page-subtitle', 'Demandes déjà validées par l\'encadrant, en attente de votre décision')

@section('tabs')
    <a href="/responsable/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/responsable/stagiaires"><i class="bi bi-mortarboard-fill"></i> Stagiaires</a>
    <a href="/responsable/demandes" class="active"><i class="bi bi-file-earmark-text-fill"></i> Demandes</a>
@endsection

@section('content')
<style>
.table-card { background: white; border-radius: 16px; border: 1px solid #eef0f4; overflow: hidden; }
.table-card table { margin: 0; }
.table-card th { background: #f8fafc; font-size: 0.8rem; color: #64748b; font-weight: 700; border-bottom: 1px solid #eef0f4; padding: 1rem; }
.table-card td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
.type-pill { font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.7rem; border-radius: 20px; background: #eef2ff; color: #4f46e5; display: inline-block; }
.btn-icon-action { border: none; background: none; color: #64748b; padding: 0.3rem 0.5rem; border-radius: 6px; text-decoration: none; }
.btn-icon-action:hover { background: #f1f5f9; }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-card">
    <table class="table">
        <thead>
            <tr>
                <th>Stagiaire</th>
                <th>Type</th>
                <th>Objet</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($demandes as $d)
            <tr>
                <td style="font-weight:600; color:#1e293b;">{{ $d->stagiaire->utilisateur->prenom ?? '—' }} {{ $d->stagiaire->utilisateur->nom ?? '' }}</td>
                <td><span class="type-pill">{{ $d->type_demande }}</span></td>
                <td>{{ $d->objet }}</td>
                <td style="font-size:0.85rem;">{{ \Carbon\Carbon::parse($d->date_demande)->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('responsable.demandes.show', $d->id_demande) }}" class="btn-icon-action" title="Voir">
                        <i class="bi bi-eye-fill"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted py-4">Aucune demande en attente de validation.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $demandes->links() }}</div>
@endsection