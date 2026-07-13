@extends('layouts.app')

@section('title', 'Accueil - STAGE-UP')

@section('content')
<style>
    .container {
        max-width: 100% !important;
        padding: 0 !important;
    }
    .mt-5 {
        margin-top: 0 !important;
    }
    body {
        margin: 0;
        background: linear-gradient(135deg, #fdf3ee 0%, #ffffff 40%, #eaf6f2 100%);
    }
    .navbar-custom {
        padding: 1.5rem 3rem 1.5rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .logo-wrap {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    .logo-icon {
        width: 34px; height: 34px;
        position: relative;
    }
    .logo-text {
        font-weight: 800;
        font-size: 1.3rem;
        color: #1e293b;
    }
    .logo-text .accent { color: #0F6E56; }

    .nav-links a {
        color: #475569;
        text-decoration: none;
        font-size: 0.9rem;
        margin-right: 1.8rem;
        font-weight: 500;
    }
    .btn-nav {
        background: #0F6E56;
        color: white;
        border: none;
        border-radius: 30px;
        padding: 0.6rem 1.6rem;
        font-weight: 600;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .hero {
        text-align: center;
        padding: 5rem 2rem 4rem;
        max-width: 780px;
        margin: 0 auto;
    }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 30px;
        padding: 0.5rem 1.2rem;
        font-size: 0.85rem;
        color: #334155;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .hero h1 {
        font-size: 3rem;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.15;
        margin-bottom: 1.2rem;
    }
    .hero p {
        color: #64748b;
        font-size: 1.1rem;
        margin-bottom: 2.5rem;
    }

    .btn-hero-main {
        display: inline-block;
        background: #0F6E56;
        color: white;
        border: none;
        border-radius: 40px;
        padding: 0.9rem 2.5rem;
        font-weight: 600;
        font-size: 1.05rem;
        text-decoration: none;
        box-shadow: 0 8px 25px rgba(15,110,86,0.25);
        transition: transform 0.2s;
    }
    .btn-hero-main:hover {
        transform: translateY(-3px);
        color: white;
    }

    .section-title {
        text-align: center;
        font-size: 1.7rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 2.5rem;
    }

    .features-section {
        max-width: 1000px;
        margin: 4rem auto 5rem;
        padding: 0 2rem;
    }
    .feature-card {
        background: white;
        border-radius: 16px;
        padding: 1.8rem;
        height: 100%;
        border: 1px solid #eef0f4;
        box-shadow: 0 4px 15px rgba(30,41,59,0.05);
        transition: transform 0.2s;
    }
    .feature-card:hover {
        transform: translateY(-6px);
    }
    .feature-icon {
        width: 50px; height: 50px;
        border-radius: 12px;
        background: #eafaf5;
        color: #0F6E56;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 1rem;
    }
    .feature-card h5 { font-weight: 700; color: #1e293b; font-size: 1.1rem; }
    .feature-card p { color: #64748b; font-size: 0.9rem; margin: 0; }

    .contact-section {
        max-width: 700px;
        margin: 2rem auto 4rem;
        padding: 2rem;
        text-align: center;
        background: white;
        border-radius: 16px;
        border: 1px solid #eef0f4;
    }
    .contact-section h3 {
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }
    .contact-section p {
        color: #64748b;
        font-size: 0.95rem;
        margin-bottom: 1.2rem;
    }
    .contact-section a {
        color: #0F6E56;
        font-weight: 600;
        text-decoration: none;
    }

    .footer-custom {
        background: #1e293b;
        color: #cbd5e1;
        padding: 3rem 3rem 2rem;
        margin-top: 3rem;
        margin-left: calc(-50vw + 50%);
        margin-right: calc(-50vw + 50%);
        width: 100vw;
    }
    .footer-top {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 2.5rem;
        max-width: 1000px;
        margin: 0 auto 2rem;
    }
    .footer-brand .logo-text {
        color: white;
    }
    .footer-brand p {
        color: #94a3b8;
        font-size: 0.88rem;
        margin-top: 0.8rem;
        max-width: 260px;
    }
    .footer-col h6 {
        color: white;
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    .footer-col a {
        display: block;
        color: #94a3b8;
        text-decoration: none;
        font-size: 0.88rem;
        margin-bottom: 0.6rem;
    }
    .footer-col a:hover { color: white; }
    .footer-bottom {
        border-top: 1px solid #334155;
        padding-top: 1.5rem;
        max-width: 1000px;
        margin: 0 auto;
        text-align: center;
        font-size: 0.82rem;
        color: #64748b;
    }
</style>

<div class="navbar-custom">
    <div class="logo-wrap">
        <svg class="logo-icon" viewBox="0 0 100 100">
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
    <div class="nav-links d-none d-md-inline-block">
        <a href="#">Fonctionnalités</a>
        <a href="#">À propos</a>
        <a href="#contact">Aide / Contact</a>
    </div>
    <a href="/login" class="btn-nav">Se connecter</a>
</div>

<div class="hero">
    <div class="hero-badge">✨ La plateforme de gestion des stages</div>
    <h1>Le logiciel qui simplifie le suivi de vos stagiaires</h1>
    <p>Tâches, absences, demandes et attestations, unifiés et automatisés sur une seule plateforme.</p>

    <a href="/login" class="btn-hero-main">Accéder à la plateforme →</a>
</div>

<div class="features-section">
    <h2 class="section-title">Tout ce dont vous avez besoin</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="feature-card">
                <div class="feature-icon">📋</div>
                <h5>Tâches & Affectations</h5>
                <p>Attribuez des missions et suivez l'avancement de chaque stagiaire en temps réel.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card">
                <div class="feature-icon">📅</div>
                <h5>Absences & Demandes</h5>
                <p>Gérez les demandes de prolongation, absences et attestations en quelques clics.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card">
                <div class="feature-icon">💬</div>
                <h5>Messagerie interne</h5>
                <p>Communiquez directement entre stagiaires, encadrants et RH sur la plateforme.</p>
            </div>
        </div>
    </div>
</div>

<div id="contact" class="contact-section">
    <h3>Besoin d'aide ?</h3>
    <p>Pour toute question sur la plateforme ou votre compte, contactez l'administrateur.</p>
    <a href="mailto:admin@gestion-stagiaires.com">admin@gestion-stagiaires.com</a>
</div>

<footer class="footer-custom">
    <div class="footer-top">
        <div class="footer-brand">
            <div class="logo-text">STAGE<span class="accent">-UP</span></div>
            <p>La plateforme qui centralise le suivi de vos stagiaires, du début à l'attestation de fin de stage.</p>
        </div>
        <div class="footer-col">
            <h6>Plateforme</h6>
            <a href="#">Fonctionnalités</a>
            <a href="/login">Se connecter</a>
        </div>
        <div class="footer-col">
            <h6>À propos</h6>
            <a href="#">Le projet</a>
            <a href="#contact">Contact</a>
        </div>
    </div>
    <div class="footer-bottom">
        © {{ date('Y') }} STAGE-UP — Tous droits réservés
    </div>
</footer>
@endsection