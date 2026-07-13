<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attestation extends Model
{
    protected $table = 'attestations';
    protected $primaryKey = 'id_attestation';
    protected $fillable = ['id_stagiaire', 'id_demande', 'numero_attestation', 'fichier_pdf', 'date_generation', 'date_validation_rh', 'statut'];

    public function stagiaire()
    {
        return $this->belongsTo(Stagiaire::class, 'id_stagiaire', 'id_stagiaire');
    }

    public function demande()
    {
        return $this->belongsTo(Demande::class, 'id_demande', 'id_demande');
    }
}