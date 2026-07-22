<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stagiaire extends Model
{
    protected $table = 'stagiaires';
    protected $primaryKey = 'id_stagiaire';
    protected $fillable = ['id_user', 'ecole', 'filiere', 'niveau_etude', 'date_debut', 'date_fin', 'statut', 'score_global', 'archive'];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_user', 'id_user');
    }

    public function affectations()
    {
        return $this->hasMany(AffectationStage::class, 'id_stagiaire', 'id_stagiaire');
    }

    public function taches()
    {
        return $this->hasMany(Tache::class, 'id_stagiaire', 'id_stagiaire');
    }

    public function absences()
    {
        return $this->hasMany(Absence::class, 'id_stagiaire', 'id_stagiaire');
    }

    public function demandes()
    {
        return $this->hasMany(Demande::class, 'id_stagiaire', 'id_stagiaire');
    }

    public function attestations()
    {
        return $this->hasMany(Attestation::class, 'id_stagiaire', 'id_stagiaire');
    }

    public function evaluations()
    {
        return $this->hasMany(EvaluationStagiaire::class, 'id_stagiaire', 'id_stagiaire');
    }

    public function rapportsHebdomadaires()
    {
        return $this->hasMany(RapportHebdomadaire::class, 'id_stagiaire', 'id_stagiaire');
    }

    public function piecesJointes()
    {
        return $this->hasMany(PieceJointe::class, 'id_stagiaire', 'id_stagiaire');
    }
    public function getStatutCalculeAttribute()
{
    // Si le statut est Archivé ou Prolongé, on ne le recalcule pas automatiquement
    if (in_array($this->statut, ['Archivé', 'Prolongé'])) {
        return $this->statut;
    }

    $aujourdhui = now()->startOfDay();
    $debut = $this->date_debut ? \Carbon\Carbon::parse($this->date_debut)->startOfDay() : null;
    $fin = $this->date_fin ? \Carbon\Carbon::parse($this->date_fin)->startOfDay() : null;

    if (!$debut) {
        return $this->statut;
    }

    if ($aujourdhui->lt($debut)) {
        return 'En attente';
    }

    if ($fin && $aujourdhui->gt($fin)) {
        return 'Terminé';
    }

    return 'En cours';
}
public function calculerScore()
{
    $taches = \App\Models\Tache::where('id_stagiaire', $this->id_stagiaire)->get();
    $totalAbsences = \App\Models\Absence::where('id_stagiaire', $this->id_stagiaire)->count();

    // Si aucune activité du tout (ni tâche, ni absence), le score reste à 0
    if ($taches->count() === 0 && $totalAbsences === 0) {
        return 0;
    }

    // 1. Avancement des tâches (40 points)
    if ($taches->count() > 0) {
        $avancementMoyen = $taches->avg('pourcentage_avancement');
        $pointsAvancement = ($avancementMoyen / 100) * 40;
    } else {
        $pointsAvancement = 0;
    }

    // 2. Respect des délais (30 points)
    $tachesTerminees = $taches->where('statut', 'Terminée');
    if ($tachesTerminees->count() > 0) {
        $tachesATemps = $tachesTerminees->filter(function ($tache) {
            if (!$tache->date_realisation || !$tache->date_echeance) return false;
            return \Carbon\Carbon::parse($tache->date_realisation)->lte(\Carbon\Carbon::parse($tache->date_echeance));
        });
        $pointsDelais = ($tachesATemps->count() / $tachesTerminees->count()) * 30;
    } elseif ($taches->count() > 0) {
        // Il y a des tâches en cours, mais aucune terminée pour l'instant
        $pointsDelais = 15;
    } else {
        $pointsDelais = 0;
    }

    // 3. Présence (30 points)
    if ($totalAbsences > 0) {
        $absencesNonJustifiees = \App\Models\Absence::where('id_stagiaire', $this->id_stagiaire)
            ->where('justifiee', false)
            ->whereNotNull('validee_par')
            ->count();
        $tauxPresence = 1 - ($absencesNonJustifiees / $totalAbsences);
        $pointsPresence = $tauxPresence * 30;
    } else {
        $pointsPresence = 30; // aucune absence déclarée = présence parfaite
    }

    $scoreTotal = round($pointsAvancement + $pointsDelais + $pointsPresence);

    return min(100, max(0, $scoreTotal));
}
}