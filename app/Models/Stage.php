<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $table = 'stages';
    protected $primaryKey = 'id_stage';
    protected $fillable = ['titre', 'description', 'objectif', 'date_debut', 'date_fin', 'departement', 'statut'];

    public function affectations()
    {
        return $this->hasMany(AffectationStage::class, 'id_stage', 'id_stage');
    }
}