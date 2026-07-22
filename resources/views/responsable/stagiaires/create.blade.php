@extends('layouts.dashboard')

@section('title', 'Ajouter un stagiaire - STAGE-UP')
@section('page-title', 'Ajouter un stagiaire')
@section('page-subtitle', 'Créez un compte pour un nouveau stagiaire')

@section('tabs')
    <a href="/responsable/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/responsable/stagiaires" class="active"><i class="bi bi-mortarboard-fill"></i> Stagiaires</a>
    <a href="/responsable/stages"><i class="bi bi-briefcase-fill"></i> Stages</a>
    <a href="/responsable/affectations"><i class="bi bi-link-45deg"></i> Affectations</a>
    <a href="/responsable/demandes"><i class="bi bi-file-earmark-text-fill"></i> Demandes</a>
@endsection

@section('content')
<style>
.form-wrapper { display: flex; justify-content: center; padding: 1rem 0 2rem; }
.form-card { background: white; border-radius: 16px; padding: 2rem; border: 1px solid #eef0f4; max-width: 600px; width: 100%; }
.form-control { border-radius: 10px; padding: 0.6rem 1rem; border: 1px solid #e2e8f0; }
.btn-save { background: #0F6E56; color: white; border: none; border-radius: 10px; padding: 0.7rem 1.5rem; font-weight: 600; width: 100%; }
.btn-save:hover { background: #0c5a46; color: white; }
.btn-cancel { color: #64748b; text-decoration: none; padding: 0.7rem 1rem; display: block; text-align: center; margin-top: 0.5rem; }
.section-label { font-size: 0.75rem; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; margin: 1.2rem 0 0.8rem; }
</style>

<div class="form-wrapper">
    <div class="form-card">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('responsable.stagiaires.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="prenom" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Téléphone</label>
                <input type="text" name="telephone" class="form-control" maxlength="10" inputmode="numeric">
            </div>
            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="mot_de_passe" class="form-control" required>
            </div>

            <div class="section-label">INFORMATIONS DU STAGE</div>
            <div class="mb-3">
                <label class="form-label">École</label>
                <input type="text" name="ecole" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Filière</label>
                <input type="text" name="filiere" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Niveau d'étude</label>
                <input type="text" name="niveau_etude" class="form-control">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date de début</label>
                    <input type="date" name="date_debut" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date de fin</label>
                    <input type="date" name="date_fin" class="form-control">
                </div>
            </div>

            <button type="submit" class="btn-save">Ajouter le stagiaire</button>
            <a href="{{ route('responsable.stagiaires.index') }}" class="btn-cancel">Annuler</a>
        </form>
    </div>
</div>
@endsection