@extends('layouts.dashboard')

@section('title', 'Nouvelle tâche - STAGE-UP')
@section('page-title', 'Nouvelle tâche')
@section('page-subtitle', 'Assignez une nouvelle tâche à un stagiaire')

@section('tabs')
    <a href="/encadrant/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/encadrant/stagiaires"><i class="bi bi-mortarboard-fill"></i> Mes stagiaires</a>
    <a href="/encadrant/taches" class="active"><i class="bi bi-list-task"></i> Tâches</a>
@endsection

@section('content')
<style>
    .form-wrapper {
        display: flex;
        justify-content: center;
        padding: 1rem 0 2rem;
    }
    .form-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        border: 1px solid #eef0f4;
        max-width: 550px;
        width: 100%;
    }
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

        @if($mesStagiaires->isEmpty())
            <div class="alert alert-warning">
                Aucun stagiaire ne vous est actuellement affecté. Contactez l'administrateur pour qu'une affectation soit créée.
            </div>
        @else
        <form method="POST" action="{{ route('encadrant.taches.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Titre de la tâche</label>
                <input type="text" name="titre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Stagiaire assigné</label>
                <select name="id_stagiaire" class="form-select" required>
                    <option value="">-- Choisir --</option>
                    @foreach($mesStagiaires as $s)
                        <option value="{{ $s->id_stagiaire }}">{{ $s->utilisateur->prenom ?? '' }} {{ $s->utilisateur->nom ?? '' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date d'échéance</label>
                    <input type="date" name="date_echeance" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Priorité</label>
                    <select name="priorite" class="form-select" required>
                        <option value="Faible">Faible</option>
                        <option value="Moyenne" selected>Moyenne</option>
                        <option value="Haute">Haute</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn-save">Assigner la tâche</button>
            <a href="{{ route('encadrant.taches.index') }}" class="btn-cancel">Annuler</a>
        </form>
        @endif
    </div>
</div>
@endsection