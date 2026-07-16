@extends('layouts.dashboard')

@section('title', 'Modifier stagiaire - STAGE-UP')
@section('page-title', 'Modifier le stagiaire')
@section('page-subtitle', ($stagiaire->utilisateur->prenom ?? '') . ' ' . ($stagiaire->utilisateur->nom ?? ''))

@section('tabs')
    <a href="/admin/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/admin/utilisateurs"><i class="bi bi-people-fill"></i> Utilisateurs</a>
    <a href="/admin/stagiaires" class="active"><i class="bi bi-mortarboard-fill"></i> Stagiaires</a>
    <a href="#"><i class="bi bi-file-earmark-text-fill"></i> Demandes</a>
@endsection

@section('content')
<style>
    .form-card { background: white; border-radius: 16px; padding: 2rem; border: 1px solid #eef0f4; max-width: 650px; }
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

    <form method="POST" action="{{ route('admin.stagiaires.update', $stagiaire->id_stagiaire) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">École</label>
            <input type="text" name="ecole" class="form-control" value="{{ old('ecole', $stagiaire->ecole) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Filière</label>
            <input type="text" name="filiere" class="form-control" value="{{ old('filiere', $stagiaire->filiere) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Niveau d'étude</label>
            <input type="text" name="niveau_etude" class="form-control" value="{{ old('niveau_etude', $stagiaire->niveau_etude) }}">
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Date de début</label>
                <input type="date" name="date_debut" class="form-control" value="{{ old('date_debut', $stagiaire->date_debut) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Date de fin</label>
                <input type="date" name="date_fin" class="form-control" value="{{ old('date_fin', $stagiaire->date_fin) }}">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Statut</label>
            <select name="statut" class="form-select" required>
                @foreach(['En attente', 'En cours', 'Terminé', 'Prolongé', 'Archivé'] as $statut)
                    <option value="{{ $statut }}" {{ old('statut', $stagiaire->statut) == $statut ? 'selected' : '' }}>{{ $statut }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="form-label">Score global (0-100)</label>
            <input type="number" name="score_global" class="form-control" min="0" max="100" value="{{ old('score_global', $stagiaire->score_global) }}">
        </div>

        <button type="submit" class="btn-save">Enregistrer</button>
        <a href="{{ route('admin.stagiaires.index') }}" class="btn-cancel">Annuler</a>
    </form>
</div>
@endsection