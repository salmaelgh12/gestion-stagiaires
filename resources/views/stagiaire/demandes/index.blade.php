@extends('layouts.dashboard')

@section('title', 'Mes demandes - STAGE-UP')
@section('page-title', 'Mes demandes')
@section('page-subtitle', 'Consultez et créez vos demandes')

@section('tabs')
    <a href="/stagiaire/dashboard"><i class="bi bi-bar-chart-fill"></i> Vue d'ensemble</a>
    <a href="/stagiaire/demandes" class="active"><i class="bi bi-file-earmark-text-fill"></i> Mes demandes</a>
    <a href="/stagiaire/absences"><i class="bi bi-calendar-x"></i> Mes absences</a>
@endsection

@section('content')
<style>
    .table-card { background: white; border-radius: 16px; border: 1px solid #eef0f4; overflow: hidden; }
    .table-card table { margin: 0; }
    .table-card th { background: #f8fafc; font-size: 0.8rem; color: #64748b; font-weight: 700; border-bottom: 1px solid #eef0f4; padding: 1rem; }
    .table-card td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
    .type-pill { font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.7rem; border-radius: 20px; background: #eef2ff; color: #4f46e5; display: inline-block; }
    .statut-pill { font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.7rem; border-radius: 20px; display: inline-block; }
    .statut-En_attente { background: #fffbeb; color: #d97706; }
    .statut-Validée { background: #eafaf5; color: #0F6E56; }
    .statut-Rejetée { background: #fef2f2; color: #dc2626; }
    .statut-Traitée { background: #eef2ff; color: #4f46e5; }
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
    <a href="{{ route('stagiaire.demandes.create') }}" class="btn-add">
        <i class="bi bi-plus-lg"></i> Nouvelle demande
    </a>
</div>

<div class="table-card">
    <table class="table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Objet</th>
                <th>Date</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($demandes as $d)
            <tr>
                <td><span class="type-pill">{{ $d->type_demande }}</span></td>
                <td>{{ $d->objet }}</td>
                <td style="font-size:0.85rem;">{{ \Carbon\Carbon::parse($d->date_demande)->format('d/m/Y') }}</td>
                <td><span class="statut-pill statut-{{ str_replace(' ', '_', $d->statut) }}">{{ $d->statut }}</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-muted py-4">Vous n'avez encore soumis aucune demande.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection