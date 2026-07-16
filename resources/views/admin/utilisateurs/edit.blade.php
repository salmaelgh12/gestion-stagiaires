@extends('layouts.dashboard')

@section('title', 'Modifier utilisateur - STAGE-UP')
@section('page-title', 'Modifier l\'utilisateur')
@section('page-subtitle', $utilisateur->prenom . ' ' . $utilisateur->nom)

@section('tabs')
    <a href="/admin/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/admin/utilisateurs" class="active"><i class="bi bi-people-fill"></i> Utilisateurs</a>
    <a href="#"><i class="bi bi-mortarboard-fill"></i> Stagiaires</a>
    <a href="#"><i class="bi bi-file-earmark-text-fill"></i> Demandes</a>
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

    <form method="POST" action="{{ route('admin.utilisateurs.update', $utilisateur->id_user) }}">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control" value="{{ old('nom', $utilisateur->nom) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Prénom</label>
                <input type="text" name="prenom" class="form-control" value="{{ old('prenom', $utilisateur->prenom) }}" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $utilisateur->email) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Téléphone</label>
            <input type="text" name="telephone" class="form-control" value="{{ old('telephone', $utilisateur->telephone) }}">
        </div>
        <div class="mb-4">
            <label class="form-label">Rôle</label>
            <select name="id_role" class="form-select" required>
                @foreach($roles as $role)
                    <option value="{{ $role->id_role }}" {{ $utilisateur->id_role == $role->id_role ? 'selected' : '' }}>
                        {{ $role->nom_role }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-save">Enregistrer les modifications</button>
        <a href="{{ route('admin.utilisateurs.index') }}" class="btn-cancel">Annuler</a>
    </form>
</div>
@endsection