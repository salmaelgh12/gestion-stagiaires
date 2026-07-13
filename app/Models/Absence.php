<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    protected $table = 'absences';
    protected $primaryKey = 'id_absence';
    protected $fillable = ['id_stagiaire', 'date_absence', 'type_absence', 'justifiee', 'motif', 'validee_par'];

    public function stagiaire()
    {
        return $this->belongsTo(Stagiaire::class, 'id_stagiaire', 'id_stagiaire');
    }

    public function validateur()
    {
        return $this->belongsTo(Utilisateur::class, 'validee_par', 'id_user');
    }
}