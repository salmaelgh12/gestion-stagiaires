@extends('layouts.app')

@section('title', 'Réinitialiser le mot de passe - STAGE-UP')

@section('content')
<style>
    .container { max-width: 100% !important; padding: 0 !important; }
    .mt-5 { margin-top: 0 !important; }
    body {
        margin: 0;
        background: linear-gradient(135deg, #fdf3ee 0%, #ffffff 40%, #eaf6f2 100%);
        min-height: 100vh;
    }
    .login-wrapper {
        min-height: 90vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    .login-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(30,41,59,0.1);
        padding: 2.5rem;
        max-width: 420px;
        width: 100%;
    }
    .login-card h4 { text-align: center; font-weight: 700; color: #1e293b; margin-bottom: 1.8rem; }
    .form-control { border-radius: 10px; padding: 0.65rem 1rem; border: 1px solid #e2e8f0; }
    .btn-login { background: #0F6E56; border: none; border-radius: 10px; padding: 0.7rem; font-weight: 600; color: white; width: 100%; }
    .btn-login:hover { background: #0c5a46; color: white; }
</style>

<div class="login-wrapper">
    <div class="login-card">
        <h4>Nouveau mot de passe</h4>

        @if ($errors->any())
            <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="/reset-password">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ $email }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nouveau mot de passe</label>
                <input type="password" name="mot_de_passe" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirmer le mot de passe</label>
                <input type="password" name="mot_de_passe_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-login">Réinitialiser</button>
        </form>
    </div>
</div>
@endsection