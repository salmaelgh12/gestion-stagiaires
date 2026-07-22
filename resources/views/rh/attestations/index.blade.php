@extends('layouts.dashboard')

@section('title', 'Attestations - STAGE-UP')
@section('page-title', 'Attestations générées')
@section('page-subtitle', 'Historique des attestations de stage')

@section('tabs')
    <a href="/rh/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/rh/demandes"><i class="bi bi-file-earmark-text-fill"></i> Demandes</a>
    <a href="/rh/attestations" class="active"><i class="bi bi-award-fill"></i> Attestations</a>
@endsection

@section('content')
<style>
.table-card { background: white; border-radius: 16px; border: 1px solid #eef0f4; overflow: hidden; }
.table-card table { margin: 0; }
.table-card th { background: #f8fafc; font-size: 0.8rem; color: #64748b; font-weight: 700; border-bottom: 1px solid #eef0f4; padding: 1rem; }
.table-card td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
.statut-pill { font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.7rem; border-radius: 20px; background: #eafaf5; color: #0F6E56; display: inline-block; }
</style>

<div class="table-card">
    <table class="table">
        <thead>
            <tr>
                <th>Numéro</th>
                <th>Stagiaire</th>
                <th>Date de génération</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attestations as $a)
            <tr>
                <td style="font-weight:600; color:#1e293b;">{{ $a->numero_attestation }}</td>
                <td>{{ $a->stagiaire->utilisateur->prenom ?? '—' }} {{ $a->stagiaire->utilisateur->nom ?? '' }}</td>
                <td style="font-size:0.85rem;">{{ \Carbon\Carbon::parse($a->date_generation)->format('d/m/Y') }}</td>
                <td><span class="statut-pill">{{ $a->statut }}</span></td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center text-muted py-4">Aucune attestation générée.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $attestations->links() }}</div>
@endsection