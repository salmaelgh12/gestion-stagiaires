@extends('layouts.app')

@section('title', 'Connexion - STAGE-UP')

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
    .login-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 2rem;
    }
    .login-logo .logo-text {
        font-weight: 800;
        font-size: 1.3rem;
        color: #1e293b;
    }
    .login-logo .accent { color: #0F6E56; }
    .login-card h4 {
        text-align: center;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1.8rem;
    }
    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #475569;
    }
    .form-control {
        border-radius: 10px;
        padding: 0.65rem 1rem;
        border: 1px solid #e2e8f0;
    }
    .form-control:focus {
        border-color: #0F6E56;
        box-shadow: 0 0 0 0.2rem rgba(15,110,86,0.12);
    }
    .btn-login {
        background: #0F6E56;
        border: none;
        border-radius: 10px;
        padding: 0.7rem;
        font-weight: 600;
        color: white;
        width: 100%;
    }
    .btn-login:hover { background: #0c5a46; color: white; }
    .forgot-link {
        color: #0F6E56;
        font-size: 0.85rem;
        text-decoration: none;
        font-weight: 500;
    }
</style>

<div class="login-wrapper">
    <div class="login-card">
        <div class="login-logo">
            <svg width="30" height="30" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="22" fill="#0F6E56"/>
                <circle cx="20" cy="24" r="9" fill="#0F6E56"/>
                <circle cx="80" cy="24" r="9" fill="#0F6E56"/>
                <circle cx="50" cy="86" r="9" fill="#0F6E56"/>
                <line x1="20" y1="24" x2="50" y2="50" stroke="#0F6E56" stroke-width="3"/>
                <line x1="80" y1="24" x2="50" y2="50" stroke="#0F6E56" stroke-width="3"/>
                <line x1="50" y1="86" x2="50" y2="50" stroke="#0F6E56" stroke-width="3"/>
            </svg>
            <div class="logo-text">STAGE<span class="accent">-UP</span></div>
        </div>

        <h4>Connexion</h4>

        @if ($errors->any())
            <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success py-2 small">{{ session('success') }}</div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <div class="mb-3">
                <label class="form-label">EMAIL</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-2">
                <label class="form-label">MOT DE PASSE</label>
                <input type="password" name="mot_de_passe" class="form-control" required>
            </div>
            <div class="text-end mb-3">
                <a href="/forgot-password" class="forgot-link">Mot de passe oublié ?</a>
            </div>
            <button type="submit" class="btn btn-login">Se connecter</button>
        </form>
    </div>
</div>
@endsection