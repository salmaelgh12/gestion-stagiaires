@extends('layouts.dashboard')

@section('title', 'Modifier tâche - STAGE-UP')
@section('page-title', 'Modifier la tâche')
@section('page-subtitle', $tache->titre)

@php
    $mesStagiairesIdsNav = \App\Models\AffectationStage::where('id_encadrant', Auth::id())->where('active', true)->pluck('id_stagiaire');
    $demandesEnAttenteCount = \App\Models\Demande::whereIn('id_stagiaire', $mesStagiairesIdsNav)
        ->whereDoesntHave('validations', fn($q) => $q->where('role_validateur', 'Encadrant'))->count();
    $tachesEnRetardCount = \App\Models\Tache::where('id_encadrant', Auth::id())
        ->where('date_echeance', '<', now())->where('statut', '!=', 'Terminée')->count();
    $absencesNonTraiteesCount = \App\Models\Absence::whereIn('id_stagiaire', $mesStagiairesIdsNav)
        ->whereNull('validee_par')->count();
@endphp
@section('tabs')
    <a href="/encadrant/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/encadrant/stagiaires"><i class="bi bi-mortarboard-fill"></i> Mes stagiaires</a>
    <a href="/encadrant/taches" class="active"><i class="bi bi-list-task"></i> Tâches @if($tachesEnRetardCount > 0)<span class="badge-count">{{ $tachesEnRetardCount > 9 ? '9+' : $tachesEnRetardCount }}</span>@endif</a>
    <a href="/encadrant/absences"><i class="bi bi-calendar-x"></i> Absences @if($absencesNonTraiteesCount > 0)<span class="badge-count">{{ $absencesNonTraiteesCount > 9 ? '9+' : $absencesNonTraiteesCount }}</span>@endif</a>
    <a href="/encadrant/demandes"><i class="bi bi-file-earmark-text-fill"></i> Demandes @if($demandesEnAttenteCount > 0)<span class="badge-count">{{ $demandesEnAttenteCount > 9 ? '9+' : $demandesEnAttenteCount }}</span>@endif</a>
@endsection

@section('content')
<style>
    .form-card { background: white; border-radius: 16px; padding: 2rem; border: 1px solid #eef0f4; max-width: 600px; }
    .form-control, .form-select { border-radius: 10px; padding: 0.6rem 1rem; border: 1px solid #e2e8f0; }
    .btn-save { background: #0F6E56; color: white; border: none; border-radius: 10px; padding: 0.7rem 1.5rem; font-weight: 600; }
    .btn-save:hover { background: #0c5a46; color: white; }
    .btn-cancel { color: #64748b; text-decoration: none; padding: 0.7rem 1rem; }
</style>

<div class="form-card">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('encadrant.taches.update', $tache->id_tache) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Titre de la tâche</label>
            <input type="text" name="titre" class="form-control" value="{{ old('titre', $tache->titre) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $tache->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Stagiaire assigné</label>
            <select name="id_stagiaire" class="form-select" required>
                @foreach($mesStagiaires as $s)
                    <option value="{{ $s->id_stagiaire }}" {{ $tache->id_stagiaire == $s->id_stagiaire ? 'selected' : '' }}>
                        {{ $s->utilisateur->prenom ?? '' }} {{ $s->utilisateur->nom ?? '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Date d'échéance</label>
                <input type="date" name="date_echeance" class="form-control" value="{{ old('date_echeance', $tache->date_echeance) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Priorité</label>
                <select name="priorite" class="form-select" required>
                    @foreach(['Faible', 'Moyenne', 'Haute'] as $p)
                        <option value="{{ $p }}" {{ $tache->priorite == $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Statut</label>
            <select name="statut" class="form-select" required>
                @foreach(['A faire', 'En cours', 'Terminée', 'Annulée'] as $s)
                    <option value="{{ $s }}" {{ $tache->statut == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="form-label">Avancement (%)</label>
            <input type="number" name="pourcentage_avancement" class="form-control" min="0" max="100" value="{{ old('pourcentage_avancement', $tache->pourcentage_avancement) }}" required>
        </div>

        <button type="submit" class="btn-save">Enregistrer</button>
        <a href="{{ route('encadrant.taches.index') }}" class="btn-cancel">Annuler</a>
    </form>
</div>
@endsection