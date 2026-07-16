@extends('layouts.dashboard')

@section('title', 'Déclarer une absence - STAGE-UP')
@section('page-title', 'Déclarer une absence')
@section('page-subtitle', 'Signalez une absence à votre encadrant')

@section('tabs')
    <a href="/stagiaire/dashboard"><i class="bi bi-bar-chart-fill"></i> Vue d'ensemble</a>
    <a href="/stagiaire/demandes"><i class="bi bi-file-earmark-text-fill"></i> Mes demandes</a>
    <a href="/stagiaire/absences" class="active"><i class="bi bi-calendar-x"></i> Mes absences</a>
@endsection

@section('content')
<style>
    .form-wrapper { display: flex; justify-content: center; padding: 1rem 0 2rem; }
    .form-card { background: white; border-radius: 16px; padding: 2rem; border: 1px solid #eef0f4; max-width: 550px; width: 100%; }
    .form-control, .form-select { border-radius: 10px; padding: 0.6rem 1rem; border: 1px solid #e2e8f0; }
    .btn-save { background: #0F6E56; color: white; border: none; border-radius: 10px; padding: 0.7rem 1.5rem; font-weight: 600; width: 100%; }
    .btn-save:hover { background: #0c5a46; color: white; }
    .btn-cancel { color: #64748b; text-decoration: none; padding: 0.7rem 1rem; display: block; text-align: center; margin-top: 0.5rem; }
</style>

<div class="form-wrapper">
    <div class="form-card">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('stagiaire.absences.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Date de l'absence</label>
                <input type="date" name="date_absence" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Type d'absence</label>
                <select name="type_absence" class="form-select" required>
                    <option value="Maladie">Maladie</option>
                    <option value="Rendez-vous personnel">Rendez-vous personnel</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label">Motif (optionnel)</label>
                <textarea name="motif" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn-save">Déclarer l'absence</button>
            <a href="{{ route('stagiaire.absences.index') }}" class="btn-cancel">Annuler</a>
        </form>
    </div>
</div>
@endsection