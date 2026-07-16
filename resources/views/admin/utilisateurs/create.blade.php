@extends('layouts.dashboard')

@section('title', 'Nouvel utilisateur - STAGE-UP')
@section('page-title', 'Nouvel utilisateur')
@section('page-subtitle', 'Créer un compte pour un membre de l\'équipe')

@section('tabs')
    <a href="/admin/dashboard"><i class="bi bi-bar-chart-fill"></i> Statistiques</a>
    <a href="/admin/utilisateurs" class="active"><i class="bi bi-people-fill"></i> Utilisateurs</a>
    <a href="#"><i class="bi bi-mortarboard-fill"></i> Stagiaires</a>
    <a href="#"><i class="bi bi-file-earmark-text-fill"></i> Demandes</a>
@endsection

@section('content')
<style>
    .wizard-wrapper { display: flex; justify-content: center; padding: 1rem 0; }
    .wizard-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(30,41,59,0.08);
        max-width: 500px;
        width: 100%;
        overflow: hidden;
    }
    .wizard-progress { height: 4px; background: #e2e8f0; }
    .wizard-progress-bar { height: 100%; background: #0F6E56; width: 33%; transition: width 0.3s; }
    .wizard-body { padding: 2.5rem; }
    .back-link {
        display: flex; align-items: center; gap: 0.5rem;
        color: #0F6E56; text-decoration: none; font-weight: 600;
        font-size: 0.9rem; margin-bottom: 1.5rem; cursor: pointer;
        background: none; border: none; padding: 0;
    }
    .wizard-title { font-size: 1.4rem; font-weight: 800; color: #1e293b; margin-bottom: 1.8rem; }
    .form-control, .form-select { border-radius: 10px; padding: 0.65rem 1rem; border: 1px solid #e2e8f0; }
    .btn-wizard {
        background: #0F6E56; color: white; border: none;
        border-radius: 10px; padding: 0.8rem; font-weight: 600;
        width: 100%; margin-top: 0.5rem;
    }
    .btn-wizard:hover { background: #0c5a46; color: white; }
    .step { display: none; }
    .step.active { display: block; }
    .summary-row {
        display: flex; justify-content: space-between;
        padding: 0.6rem 0; border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }
    .summary-row .label { color: #64748b; }
    .summary-row .value { color: #1e293b; font-weight: 600; }
    .field-error {
        color: #dc2626;
        font-size: 0.8rem;
        margin-top: 0.4rem;
        display: none;
        align-items: center;
        gap: 0.3rem;
    }
    .field-error.show { display: flex; }
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc2626 !important;
    }
</style>

<div class="wizard-wrapper">
    <div class="wizard-card">
        <div class="wizard-progress"><div class="wizard-progress-bar" id="progressBar"></div></div>
        <div class="wizard-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.utilisateurs.store') }}" id="wizardForm">
                @csrf

                <!-- ÉTAPE 1 : Infos générales -->
                <div class="step active" id="step1">
                    <div class="wizard-title">Informations générales</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" id="inputNom" class="form-control" value="{{ old('nom') }}" required>
                            <div class="field-error" id="errorNom"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="prenom" id="inputPrenom" class="form-control" value="{{ old('prenom') }}" required>
                            <div class="field-error" id="errorPrenom"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="inputEmail" class="form-control" value="{{ old('email') }}" required autocomplete="off">
                        <div class="field-error" id="errorEmail"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="telephone" class="form-control" value="{{ old('telephone') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="mot_de_passe" id="inputPassword" class="form-control" required autocomplete="new-password">
                        <div class="field-error" id="errorPassword"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rôle</label>
                        <select name="id_role" id="roleSelect" class="form-select" required>
                            <option value="">-- Choisir --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id_role }}" data-role-name="{{ $role->nom_role }}">{{ $role->nom_role }}</option>
                            @endforeach
                        </select>
                        <div class="field-error" id="errorRole"></div>
                    </div>

                    <button type="button" class="btn-wizard" id="btnNext1">Continuer</button>
                    <a href="{{ route('admin.utilisateurs.index') }}" class="d-block text-center mt-3 text-muted" style="font-size:0.85rem;">Annuler</a>
                </div>

                <!-- ÉTAPE 2 (Stagiaire uniquement) : infos spécifiques -->
                <div class="step" id="step2-stagiaire">
                    <button type="button" class="back-link" id="btnBackToStep1FromStagiaire">
                        <i class="bi bi-arrow-left"></i> Étape précédente
                    </button>
                    <div class="wizard-title">Informations du stagiaire</div>

                    <div class="mb-3">
                        <label class="form-label">École</label>
                        <input type="text" name="ecole" id="inputEcole" class="form-control" value="{{ old('ecole') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Filière</label>
                        <input type="text" name="filiere" id="inputFiliere" class="form-control" value="{{ old('filiere') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Niveau d'étude</label>
                        <input type="text" name="niveau_etude" id="inputNiveau" class="form-control" value="{{ old('niveau_etude') }}" placeholder="Ex: 3ème année">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de début</label>
                            <input type="date" name="date_debut" id="inputDateDebut" class="form-control" value="{{ old('date_debut') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de fin</label>
                            <input type="date" name="date_fin" id="inputDateFin" class="form-control" value="{{ old('date_fin') }}">
                        </div>
                    </div>

                    <button type="button" class="btn-wizard" id="btnNext2">Continuer</button>
                </div>

                <!-- ÉTAPE FINALE : Confirmation -->
                <div class="step" id="stepConfirm">
                    <button type="button" class="back-link" id="btnBackToPrevious">
                        <i class="bi bi-arrow-left"></i> Étape précédente
                    </button>
                    <div class="wizard-title">Confirmation</div>
                    <p class="text-muted mb-3">Vérifiez les informations puis créez le compte.</p>

                    <div class="mb-4">
                        <div class="summary-row"><span class="label">Nom complet</span><span class="value" id="summaryNom"></span></div>
                        <div class="summary-row"><span class="label">Email</span><span class="value" id="summaryEmail"></span></div>
                        <div class="summary-row"><span class="label">Rôle</span><span class="value" id="summaryRole"></span></div>
                        <div class="summary-row" id="summaryEcoleRow" style="display:none;"><span class="label">École</span><span class="value" id="summaryEcole"></span></div>
                    </div>

                    <button type="submit" class="btn-wizard">Créer l'utilisateur</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const step1 = document.getElementById('step1');
        const step2Stagiaire = document.getElementById('step2-stagiaire');
        const stepConfirm = document.getElementById('stepConfirm');
        const progressBar = document.getElementById('progressBar');
        const roleSelect = document.getElementById('roleSelect');

        let isStagiaire = false;

        function showStep(el) {
            [step1, step2Stagiaire, stepConfirm].forEach(s => s.classList.remove('active'));
            el.classList.add('active');
        }

        function fillSummary() {
            document.getElementById('summaryNom').textContent =
                document.getElementById('inputNom').value + ' ' + document.getElementById('inputPrenom').value;
            document.getElementById('summaryEmail').textContent = document.getElementById('inputEmail').value;

            const selectedOption = roleSelect.options[roleSelect.selectedIndex];
            const roleName = selectedOption ? selectedOption.getAttribute('data-role-name') : '';
            document.getElementById('summaryRole').textContent = roleName;

            if (isStagiaire) {
                document.getElementById('summaryEcoleRow').style.display = 'flex';
                document.getElementById('summaryEcole').textContent = document.getElementById('inputEcole').value || '—';
            } else {
                document.getElementById('summaryEcoleRow').style.display = 'none';
            }
        }

        document.getElementById('btnNext1').addEventListener('click', function () {
            const fields = [
                { input: document.getElementById('inputNom'), error: document.getElementById('errorNom'), msg: 'Veuillez saisir le nom.' },
                { input: document.getElementById('inputPrenom'), error: document.getElementById('errorPrenom'), msg: 'Veuillez saisir le prénom.' },
                { input: document.getElementById('inputEmail'), error: document.getElementById('errorEmail'), msg: 'Veuillez saisir une adresse email valide.' },
                { input: document.getElementById('inputPassword'), error: document.getElementById('errorPassword'), msg: 'Veuillez saisir un mot de passe.' },
                { input: roleSelect, error: document.getElementById('errorRole'), msg: 'Veuillez choisir un rôle.' },
            ];

            let firstInvalid = null;

            fields.forEach(f => {
                const isValid = f.input.checkValidity() && f.input.value.trim() !== '';
                if (!isValid) {
                    f.error.textContent = '⚠ ' + f.msg;
                    f.error.classList.add('show');
                    f.input.classList.add('is-invalid');
                    if (!firstInvalid) firstInvalid = f.input;
                } else {
                    f.error.classList.remove('show');
                    f.input.classList.remove('is-invalid');
                }
            });

            if (firstInvalid) {
                firstInvalid.focus();
                return;
            }

            const selectedOption = roleSelect.options[roleSelect.selectedIndex];
            const roleName = (selectedOption.getAttribute('data-role-name') || '').trim().toLowerCase();
            isStagiaire = (roleName === 'stagiaire');

            if (isStagiaire) {
                progressBar.style.width = '66%';
                showStep(step2Stagiaire);
            } else {
                progressBar.style.width = '100%';
                fillSummary();
                showStep(stepConfirm);
            }
        });

        document.getElementById('btnNext2').addEventListener('click', function () {
            progressBar.style.width = '100%';
            fillSummary();
            showStep(stepConfirm);
        });

        document.getElementById('btnBackToStep1FromStagiaire').addEventListener('click', function () {
            progressBar.style.width = '33%';
            showStep(step1);
        });

        document.getElementById('btnBackToPrevious').addEventListener('click', function () {
            if (isStagiaire) {
                progressBar.style.width = '66%';
                showStep(step2Stagiaire);
            } else {
                progressBar.style.width = '33%';
                showStep(step1);
            }
        });
    });
</script>
@endsection