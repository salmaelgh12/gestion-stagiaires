<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModeleIa extends Model
{
    protected $table = 'modeles_ia';
    protected $primaryKey = 'id_modele';
    protected $fillable = ['nom_modele', 'fournisseur', 'actif'];

    public function messagesIa()
    {
        return $this->hasMany(MessageIa::class, 'modele_utilise', 'id_modele');
    }
}