@extends('layouts.dashboard')

@section('title', 'Modifier le mot de passe - STAGE-UP')
@section('page-title', 'Modifier le mot de passe')
@section('page-subtitle', $utilisateur->prenom . ' ' . $utilisateur->nom)

@section('tabs')
    <a href="/admin/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/admin/utilisateurs" class="active"><i class="bi bi-people-fill"></i> Utilisateurs</a>
    <a href="/admin/stagiaires"><i class="bi bi-mortarboard-fill"></i> Stagiaires</a>
    <a href="/admin/demandes"><i class="bi bi-file-earmark-text-fill"></i> Demandes</a>
@endsection

@section('content')
<style>
    .form-card { background: white; border-radius: 16px; padding: 2rem; border: 1px solid #eef0f4; max-width: 500px; }
    .form-control { border-radius: 10px; padding: 0.6rem 1rem; border: 1px solid #e2e8f0; }
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

    <form method="POST" action="{{ route('admin.utilisateurs.password.update', $utilisateur->id_user) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nouveau mot de passe</label>
            <input type="password" name="mot_de_passe" class="form-control" required autocomplete="new-password">
        </div>
        <div class="mb-4">
            <label class="form-label">Confirmer le mot de passe</label>
            <input type="password" name="mot_de_passe_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn-save">Enregistrer le nouveau mot de passe</button>
        <a href="{{ route('admin.utilisateurs.index') }}" class="btn-cancel">Annuler</a>
    </form>
</div>
@endsection