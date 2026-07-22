@extends('layouts.dashboard')

@section('title', 'Nouveau stage - STAGE-UP')
@section('page-title', 'Nouveau stage')
@section('page-subtitle', 'Créez une nouvelle offre de stage')

@section('tabs')
    <a href="/responsable/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/responsable/stagiaires"><i class="bi bi-mortarboard-fill"></i> Stagiaires</a>
    <a href="/responsable/stages" class="active"><i class="bi bi-briefcase-fill"></i> Stages</a>
    <a href="/responsable/affectations"><i class="bi bi-link-45deg"></i> Affectations</a>
    <a href="/responsable/demandes"><i class="bi bi-file-earmark-text-fill"></i> Demandes</a>
@endsection

@section('content')
<style>
.form-wrapper { display: flex; justify-content: center; padding: 1rem 0 2rem; }
.form-card { background: white; border-radius: 16px; padding: 2rem; border: 1px solid #eef0f4; max-width: 600px; width: 100%; }
.form-control, .form-select { border-radius: 10px; padding: 0.6rem 1rem; border: 1px solid #e2e8f0; }
.btn-save { background: #0F6E56; color: white; border: none; border-radius: 10px; padding: 0.7rem 1.5rem; font-weight: 600; width: 100%; }
.btn-save:hover { background: #0c5a46; color: white; }
.btn-cancel { color: #64748b; text-decoration: none; padding: 0.7rem 1rem; display: block; text-align: center; margin-top: 0.5rem; }
</style>

<div class="form-wrapper">
    <div class="form-card">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('responsable.stages.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Titre du stage</label>
                <input type="text" name="titre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Objectif</label>
                <textarea name="objectif" class="form-control" rows="2"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Département</label>
                <input type="text" name="departement" class="form-control">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date de début</label>
                    <input type="date" name="date_debut" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date de fin</label>
                    <input type="date" name="date_fin" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn-save">Créer le stage</button>
            <a href="{{ route('responsable.stages.index') }}" class="btn-cancel">Annuler</a>
        </form>
    </div>
</div>
@endsection