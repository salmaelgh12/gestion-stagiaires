@extends('layouts.dashboard')

@section('title', 'Mes absences - STAGE-UP')
@section('page-title', 'Mes absences')
@section('page-subtitle', 'Déclarez et suivez vos absences')

@section('tabs')
    <a href="/stagiaire/dashboard"><i class="bi bi-bar-chart-fill"></i> Vue d'ensemble</a>
    <a href="/stagiaire/demandes"><i class="bi bi-file-earmark-text-fill"></i> Mes demandes</a>
    <a href="/stagiaire/absences" class="active"><i class="bi bi-calendar-x"></i> Mes absences</a>
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
    .btn-add {
        background: #0F6E56; color: white; border: none;
        border-radius: 10px; padding: 0.6rem 1.3rem; font-weight: 600;
        text-decoration: none; font-size: 0.9rem;
    }
    .btn-add:hover { background: #0c5a46; color: white; }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('stagiaire.absences.create') }}" class="btn-add">
        <i class="bi bi-plus-lg"></i> Déclarer une absence
    </a>
</div>

<div class="table-card">
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Motif</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absences as $a)
            <tr>
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
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-muted py-4">Vous n'avez déclaré aucune absence.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection