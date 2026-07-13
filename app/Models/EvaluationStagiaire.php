<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationStagiaire extends Model
{
    protected $table = 'evaluations_stagiaire';
    protected $primaryKey = 'id_evaluation';
    protected $fillable = ['id_stagiaire', 'id_encadrant', 'note_technique', 'note_comportement', 'note_assiduite', 'commentaire', 'date_evaluation'];

    public function stagiaire()
    {
        return $this->belongsTo(Stagiaire::class, 'id_stagiaire', 'id_stagiaire');
    }

    public function encadrant()
    {
        return $this->belongsTo(Utilisateur::class, 'id_encadrant', 'id_user');
    }
}