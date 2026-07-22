<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UtilisateurController;
use App\Http\Controllers\Admin\StagiaireController;
use App\Http\Controllers\Admin\DemandeController;
use App\Http\Controllers\ConversationIaController;
use App\Http\Controllers\Stagiaire\DashboardController as StagiaireDashboardController;
use App\Http\Controllers\Stagiaire\DemandeController as StagiaireDemandeController;
use App\Http\Controllers\Stagiaire\AbsenceController as StagiaireAbsenceController;
use App\Http\Controllers\Encadrant\DashboardController as EncadrantDashboardController;
use App\Http\Controllers\Encadrant\StagiaireController as EncadrantStagiaireController;
use App\Http\Controllers\Encadrant\TacheController;
use App\Http\Controllers\Encadrant\AbsenceController as EncadrantAbsenceController;
use App\Http\Controllers\Encadrant\DemandeController as EncadrantDemandeController;
use App\Http\Controllers\Responsable\DashboardController as ResponsableDashboardController;
use App\Http\Controllers\Responsable\StagiaireController as ResponsableStagiaireController;
use App\Http\Controllers\Responsable\DemandeController as ResponsableDemandeController;
use App\Http\Controllers\Responsable\StageController as ResponsableStageController;
use App\Http\Controllers\Responsable\AffectationController;
use App\Http\Controllers\Responsable\AjoutStagiaireController;
use App\Http\Controllers\RH\DashboardController as RHDashboardController;
use App\Http\Controllers\RH\DemandeController as RHDemandeController;
use App\Http\Controllers\RH\AttestationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/utilisateurs', [UtilisateurController::class, 'index'])->name('utilisateurs.index');
    Route::get('/utilisateurs/create', [UtilisateurController::class, 'create'])->name('utilisateurs.create');
    Route::post('/utilisateurs', [UtilisateurController::class, 'store'])->name('utilisateurs.store');
    Route::get('/utilisateurs/{utilisateur}/edit', [UtilisateurController::class, 'edit'])->name('utilisateurs.edit');
    Route::put('/utilisateurs/{utilisateur}', [UtilisateurController::class, 'update'])->name('utilisateurs.update');
    Route::patch('/utilisateurs/{utilisateur}/toggle', [UtilisateurController::class, 'toggleActive'])->name('utilisateurs.toggle');
    Route::delete('/utilisateurs/{utilisateur}', [UtilisateurController::class, 'destroy'])->name('utilisateurs.destroy');
    Route::get('/utilisateurs/{utilisateur}/password', [UtilisateurController::class, 'showResetPassword'])->name('utilisateurs.password');
    Route::put('/utilisateurs/{utilisateur}/password', [UtilisateurController::class, 'resetPassword'])->name('utilisateurs.password.update');

    Route::get('/stagiaires', [StagiaireController::class, 'index'])->name('stagiaires.index');
    Route::get('/stagiaires/{stagiaire}/edit', [StagiaireController::class, 'edit'])->name('stagiaires.edit');
    Route::put('/stagiaires/{stagiaire}', [StagiaireController::class, 'update'])->name('stagiaires.update');

    Route::get('/demandes', [DemandeController::class, 'index'])->name('demandes.index');
    Route::get('/demandes/{demande}', [DemandeController::class, 'show'])->name('demandes.show');
});

Route::middleware(['auth', 'role:Stagiaire'])->prefix('stagiaire')->name('stagiaire.')->group(function () {
    Route::get('/dashboard', [StagiaireDashboardController::class, 'index'])->name('dashboard');

    Route::get('/demandes', [StagiaireDemandeController::class, 'index'])->name('demandes.index');
    Route::get('/demandes/create', [StagiaireDemandeController::class, 'create'])->name('demandes.create');
    Route::post('/demandes', [StagiaireDemandeController::class, 'store'])->name('demandes.store');

    Route::get('/absences', [StagiaireAbsenceController::class, 'index'])->name('absences.index');
    Route::get('/absences/create', [StagiaireAbsenceController::class, 'create'])->name('absences.create');
    Route::post('/absences', [StagiaireAbsenceController::class, 'store'])->name('absences.store');
});

Route::middleware(['auth', 'role:Encadrant'])->prefix('encadrant')->name('encadrant.')->group(function () {
    Route::get('/dashboard', [EncadrantDashboardController::class, 'index'])->name('dashboard');

    Route::get('/stagiaires', [EncadrantStagiaireController::class, 'index'])->name('stagiaires.index');

    Route::get('/taches', [TacheController::class, 'index'])->name('taches.index');
    Route::get('/taches/create', [TacheController::class, 'create'])->name('taches.create');
    Route::post('/taches', [TacheController::class, 'store'])->name('taches.store');
    Route::get('/taches/{tache}/edit', [TacheController::class, 'edit'])->name('taches.edit');
    Route::put('/taches/{tache}', [TacheController::class, 'update'])->name('taches.update');
    Route::delete('/taches/{tache}', [TacheController::class, 'destroy'])->name('taches.destroy');

    Route::get('/absences', [EncadrantAbsenceController::class, 'index'])->name('absences.index');
    Route::patch('/absences/{absence}/valider', [EncadrantAbsenceController::class, 'valider'])->name('absences.valider');
    Route::patch('/absences/{absence}/refuser', [EncadrantAbsenceController::class, 'refuser'])->name('absences.refuser');

    Route::get('/demandes', [EncadrantDemandeController::class, 'index'])->name('demandes.index');
    Route::get('/demandes/{demande}', [EncadrantDemandeController::class, 'show'])->name('demandes.show');
    Route::post('/demandes/{demande}/valider', [EncadrantDemandeController::class, 'valider'])->name('demandes.valider');
    Route::post('/demandes/{demande}/rejeter', [EncadrantDemandeController::class, 'rejeter'])->name('demandes.rejeter');
});

Route::middleware(['auth', 'role:Responsable de compétence'])->prefix('responsable')->name('responsable.')->group(function () {
    Route::get('/dashboard', [ResponsableDashboardController::class, 'index'])->name('dashboard');

    Route::get('/stagiaires', [ResponsableStagiaireController::class, 'index'])->name('stagiaires.index');
    Route::get('/stagiaires/create', [AjoutStagiaireController::class, 'create'])->name('stagiaires.create');
    Route::post('/stagiaires', [AjoutStagiaireController::class, 'store'])->name('stagiaires.store');

    Route::get('/demandes', [ResponsableDemandeController::class, 'index'])->name('demandes.index');
    Route::get('/demandes/{demande}', [ResponsableDemandeController::class, 'show'])->name('demandes.show');
    Route::post('/demandes/{demande}/valider', [ResponsableDemandeController::class, 'valider'])->name('demandes.valider');
    Route::post('/demandes/{demande}/rejeter', [ResponsableDemandeController::class, 'rejeter'])->name('demandes.rejeter');

    Route::get('/stages', [ResponsableStageController::class, 'index'])->name('stages.index');
    Route::get('/stages/create', [ResponsableStageController::class, 'create'])->name('stages.create');
    Route::post('/stages', [ResponsableStageController::class, 'store'])->name('stages.store');

    Route::get('/affectations', [AffectationController::class, 'index'])->name('affectations.index');
    Route::get('/affectations/create', [AffectationController::class, 'create'])->name('affectations.create');
    Route::post('/affectations', [AffectationController::class, 'store'])->name('affectations.store');
});

Route::middleware(['auth', 'role:RH'])->prefix('rh')->name('rh.')->group(function () {
    Route::get('/dashboard', [RHDashboardController::class, 'index'])->name('dashboard');

    Route::get('/demandes', [RHDemandeController::class, 'index'])->name('demandes.index');
    Route::get('/demandes/{demande}', [RHDemandeController::class, 'show'])->name('demandes.show');
    Route::post('/demandes/{demande}/valider', [RHDemandeController::class, 'valider'])->name('demandes.valider');
    Route::post('/demandes/{demande}/rejeter', [RHDemandeController::class, 'rejeter'])->name('demandes.rejeter');

    Route::get('/attestations', [AttestationController::class, 'index'])->name('attestations.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/ia/historique', [ConversationIaController::class, 'history'])->name('ia.history');
    Route::post('/ia/envoyer', [ConversationIaController::class, 'send'])->name('ia.send');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{utilisateur}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{utilisateur}', [MessageController::class, 'store'])->name('messages.store');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');