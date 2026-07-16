@extends('layouts.dashboard')

@section('title', 'Absences - STAGE-UP')
@section('page-title', 'Gestion des absences')
@section('page-subtitle', 'Validez les absences de vos stagiaires')

@section('tabs')
    <a href="/encadrant/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/encadrant/stagiaires"><i class="bi bi-mortarboard-fill"></i> Mes stagiaires</a>
    <a href="/encadrant/taches"><i class="bi bi-list-task"></i> Tâches</a>
    <a href="/encadrant/absences" class="active"><i class="bi bi-calendar-x"></i> Absences</a>
@endsection

@section('content')
<style>
    .table-card { background: white; border-radius: 16px; border: 1px solid #eef0f4; overflow: hidden; }
    .table-card table { margin: 0; }
    .table-card th { background: #f8fafc; font-size: 0.8rem; color: #64748b; font-weight: 700; border-bottom: 1px solid #eef0f4; padding: 1rem; }
    .table-card td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
    .statut-pill { font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.7rem; border-radius: 20px; display: inline-block; }
    .statut-attente { background: #fffbeb; color: #d97706; }
    .statut-justifiee { background: #eafaf5; color: #0F6E56; }
    .statut-refusee { background: #fef2f2; color: #dc2626; }
    .btn-action { border: none; border-radius: 8px; padding: 0.4rem 0.9rem; font-size: 0.8rem; font-weight: 600; }
    .btn-valider { background: #eafaf5; color: #0F6E56; }
    .btn-refuser { background: #fef2f2; color: #dc2626; }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-card">
    <table class="table">
        <thead>
            <tr>
                <th>Stagiaire</th>
                <th>Date</th>
                <th>Type</th>
                <th>Motif</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absences as $a)
            <tr>
                <td style="font-weight:600; color:#1e293b;">{{ $a->stagiaire->utilisateur->prenom ?? '—' }} {{ $a->stagiaire->utilisateur->nom ?? '' }}</td>
                <td>{{ \Carbon\Carbon::parse($a->date_absence)->format('d/m/Y') }}</td>
                <td>{{ $a->type_absence }}</td>
                <td class="text-muted">{{ $a->motif ?: '—' }}</td>
                <td>
                    @if(is_null($a->validee_par))
                        <span class="statut-pill statut-attente">En attente</span>
                    @elseif($a->justifiee)
                        <span class="statut-pill statut-justifiee">Justifiée</span>
                    @else
                        <span class="statut-pill statut-refusee">Non justifiée</span>
                    @endif
                </td>
                <td>
                    @if(is_null($a->validee_par))
                        <form method="POST" action="{{ route('encadrant.absences.valider', $a->id_absence) }}" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-action btn-valider">Valider</button>
                        </form>
                        <form method="POST" action="{{ route('encadrant.absences.refuser', $a->id_absence) }}" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-action btn-refuser">Refuser</button>
                        </form>
                    @else
                        <span class="text-muted" style="font-size:0.85rem;">Traité</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted py-4">Aucune absence déclarée.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $absences->links() }}</div>
@endsection