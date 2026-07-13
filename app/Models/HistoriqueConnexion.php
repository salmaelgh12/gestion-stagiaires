<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriqueConnexion extends Model
{
    protected $table = 'historique_connexion';
    protected $primaryKey = 'id_historique';
    public $timestamps = false;
    protected $fillable = ['id_user', 'adresse_ip', 'navigateur', 'date_connexion'];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_user', 'id_user');
    }
}