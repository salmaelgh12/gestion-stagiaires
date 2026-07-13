<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValidationDemande extends Model
{
    protected $table = 'validations_demande';
    protected $primaryKey = 'id_validation';
    public $timestamps = false;
    protected $fillable = ['id_demande', 'id_validateur', 'role_validateur', 'decision', 'commentaire', 'date_validation'];

    public function demande()
    {
        return $this->belongsTo(Demande::class, 'id_demande', 'id_demande');
    }

    public function validateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_validateur', 'id_user');
    }
}