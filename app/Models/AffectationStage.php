<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffectationStage extends Model
{
    protected $table = 'affectations_stage';
    protected $primaryKey = 'id_affectation';
    protected $fillable = ['id_stagiaire', 'id_stage', 'id_encadrant', 'id_responsable_competence', 'date_affectation', 'active'];

    public function stagiaire()
    {
        return $this->belongsTo(Stagiaire::class, 'id_stagiaire', 'id_stagiaire');
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'id_stage', 'id_stage');
    }

    public function encadrant()
    {
        return $this->belongsTo(Utilisateur::class, 'id_encadrant', 'id_user');
    }

    public function responsableCompetence()
    {
        return $this->belongsTo(Utilisateur::class, 'id_responsable_competence', 'id_user');
    }
}