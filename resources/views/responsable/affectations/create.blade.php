@extends('layouts.dashboard')

@section('title', 'Nouvelle affectation - STAGE-UP')
@section('page-title', 'Nouvelle affectation')
@section('page-subtitle', 'Associez un stagiaire à un stage et un encadrant')

@section('tabs')
    <a href="/responsable/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/responsable/stagiaires"><i class="bi bi-mortarboard-fill"></i> Stagiaires</a>
    <a href="/responsable/stages"><i class="bi bi-briefcase-fill"></i> Stages</a>
    <a href="/responsable/affectations" class="active"><i class="bi bi-link-45deg"></i> Affectations</a>
    <a href="/responsable/demandes"><i class="bi bi-file-earmark-text-fill"></i> Demandes</a>
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
                <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        @if($stagiaires->isEmpty() || $stages->isEmpty() || $encadrants->isEmpty())
            <div class="alert alert-warning">
                @if($stagiaires->isEmpty()) Aucun stagiaire disponible (tous déjà affectés, ou aucun créé). @endif
                @if($stages->isEmpty()) Aucun stage ouvert disponible. @endif
                @if($encadrants->isEmpty()) Aucun encadrant actif disponible. @endif
            </div>
        @else
        <form method="POST" action="{{ route('responsable.affectations.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Stagiaire</label>
                <select name="id_stagiaire" class="form-select" required>
                    <option value="">-- Choisir --</option>
                    @foreach($stagiaires as $s)
                        <option value="{{ $s->id_stagiaire }}">{{ $s->utilisateur->prenom ?? '' }} {{ $s->utilisateur->nom ?? '' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Stage</label>
                <select name="id_stage" class="form-select" required>
                    <option value="">-- Choisir --</option>
                    @foreach($stages as $s)
                        <option value="{{ $s->id_stage }}">{{ $s->titre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label">Encadrant</label>
                <select name="id_encadrant" class="form-select" required>
                    <option value="">-- Choisir --</option>
                    @foreach($encadrants as $e)
                        <option value="{{ $e->id_user }}">{{ $e->prenom }} {{ $e->nom }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-save">Créer l'affectation</button>
            <a href="{{ route('responsable.affectations.index') }}" class="btn-cancel">Annuler</a>
        </form>
        @endif
    </div>
</div>
@endsection