<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RapportHebdomadaire extends Model
{
    protected $table = 'rapports_hebdomadaires';
    protected $primaryKey = 'id_rapport';
    public $timestamps = false;
    protected $fillable = ['id_stagiaire', 'periode_debut', 'periode_fin', 'resume_activites', 'score_performance', 'genere_par_ia', 'date_generation'];

    public function stagiaire()
    {
        return $this->belongsTo(Stagiaire::class, 'id_stagiaire', 'id_stagiaire');
    }
}