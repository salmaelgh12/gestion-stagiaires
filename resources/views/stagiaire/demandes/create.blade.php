@extends('layouts.dashboard')

@section('title', 'Nouvelle demande - STAGE-UP')
@section('page-title', 'Nouvelle demande')
@section('page-subtitle', 'Soumettez une demande à votre encadrement')

@section('tabs')
    <a href="/stagiaire/dashboard"><i class="bi bi-bar-chart-fill"></i> Vue d'ensemble</a>
    <a href="/stagiaire/demandes" class="active"><i class="bi bi-file-earmark-text-fill"></i> Mes demandes</a>
    <a href="/stagiaire/absences"><i class="bi bi-calendar-x"></i> Mes absences</a>
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
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('stagiaire.demandes.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Type de demande</label>
                <select name="type_demande" class="form-select" required>
                    <option value="">-- Choisir --</option>
                    <option value="Attestation">Attestation</option>
                    <option value="Prolongation">Prolongation</option>
                    <option value="Absence">Absence</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Objet</label>
                <input type="text" name="objet" class="form-control" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4"></textarea>
            </div>

            <button type="submit" class="btn-save">Envoyer la demande</button>
            <a href="{{ route('stagiaire.demandes.index') }}" class="btn-cancel">Annuler</a>
        </form>
    </div>
</div>
@endsection