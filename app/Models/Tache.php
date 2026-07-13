<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tache extends Model
{
    protected $table = 'taches';
    protected $primaryKey = 'id_tache';
    protected $fillable = ['titre', 'description', 'id_stagiaire', 'id_encadrant', 'date_creation', 'date_echeance', 'date_realisation', 'priorite', 'statut', 'pourcentage_avancement'];

    public function stagiaire()
    {
        return $this->belongsTo(Stagiaire::class, 'id_stagiaire', 'id_stagiaire');
    }

    public function encadrant()
    {
        return $this->belongsTo(Utilisateur::class, 'id_encadrant', 'id_user');
    }
}