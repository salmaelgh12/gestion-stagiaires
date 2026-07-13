<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    protected $table = 'demandes';
    protected $primaryKey = 'id_demande';
    protected $fillable = ['id_stagiaire', 'type_demande', 'objet', 'description', 'statut', 'date_demande'];

    public function stagiaire()
    {
        return $this->belongsTo(Stagiaire::class, 'id_stagiaire', 'id_stagiaire');
    }

    public function validations()
    {
        return $this->hasMany(ValidationDemande::class, 'id_demande', 'id_demande');
    }

    public function attestation()
    {
        return $this->hasOne(Attestation::class, 'id_demande', 'id_demande');
    }
}