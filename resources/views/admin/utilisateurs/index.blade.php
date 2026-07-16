@extends('layouts.dashboard')

@section('title', 'Utilisateurs - STAGE-UP')
@section('page-title', 'Gestion des utilisateurs')
@section('page-subtitle', 'Créez et gérez les comptes de la plateforme')

@section('tabs')
    <a href="/admin/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/admin/utilisateurs" class="active"><i class="bi bi-people-fill"></i> Utilisateurs</a>
    <a href="/admin/stagiaires"><i class="bi bi-mortarboard-fill"></i> Stagiaires</a>
    <a href="/admin/demandes"><i class="bi bi-file-earmark-text-fill"></i> Demandes @if($demandesEnAttente > 0)<span class="badge-count">{{ $demandesEnAttente }}</span>@endif</a>
@endsection

@section('content')
<style>
    .table-card { background: white; border-radius: 16px; border: 1px solid #eef0f4; overflow: hidden; }
    .table-card table { margin: 0; }
    .table-card th { background: #f8fafc; font-size: 0.8rem; color: #64748b; font-weight: 700; border-bottom: 1px solid #eef0f4; padding: 1rem; }
    .table-card td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
    .avatar-sm {
        width: 36px; height: 36px; border-radius: 50%;
        background: #eafaf5; color: #0F6E56;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.8rem;
    }
    .role-pill {
        background: #eef2ff; color: #4f46e5;
        font-size: 0.75rem; font-weight: 600;
        padding: 0.25rem 0.7rem; border-radius: 20px;
    }
    .status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 5px; }
    .btn-add {
        background: #0F6E56; color: white; border: none;
        border-radius: 10px; padding: 0.6rem 1.3rem; font-weight: 600;
        text-decoration: none; font-size: 0.9rem;
    }
    .btn-add:hover { background: #0c5a46; color: white; }
    .btn-icon-action {
        border: none; background: none; color: #64748b;
        padding: 0.3rem 0.5rem; border-radius: 6px;
    }
    .btn-icon-action:hover { background: #f1f5f9; }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.utilisateurs.create') }}" class="btn-add">
        <i class="bi bi-plus-lg"></i> Nouvel utilisateur
    </a>
</div>

<div class="table-card">
    <table class="table">
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($utilisateurs as $u)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-sm">{{ substr($u->prenom, 0, 1) }}{{ substr($u->nom, 0, 1) }}</div>
                        <div style="font-weight:600; color:#1e293b;">{{ $u->prenom }} {{ $u->nom }}</div>
                    </div>
                </td>
                <td class="text-muted">{{ $u->email }}</td>
                <td><span class="role-pill">{{ $u->role->nom_role }}</span></td>
                <td>
                    <span class="status-dot" style="background:{{ $u->actif ? '#22c55e' : '#94a3b8' }};"></span>
                    {{ $u->actif ? 'Actif' : 'Inactif' }}
                </td>
                <td>
                    <a href="{{ route('admin.utilisateurs.password', $u->id_user) }}" class="btn-icon-action" title="Modifier le mot de passe">
                        <i class="bi bi-key-fill"></i>
                    </a>
                    <a href="{{ route('admin.utilisateurs.edit', $u->id_user) }}" class="btn-icon-action" title="Modifier">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.utilisateurs.toggle', $u->id_user) }}" class="d-inline">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-icon-action" title="Activer/Désactiver">
                            <i class="bi bi-toggle2-{{ $u->actif ? 'on' : 'off' }}"></i>
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.utilisateurs.destroy', $u->id_user) }}" class="d-inline" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-icon-action text-danger" title="Supprimer">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection