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
}