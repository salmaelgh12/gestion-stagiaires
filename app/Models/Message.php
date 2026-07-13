<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $primaryKey = 'id_message';
    protected $fillable = ['expediteur_id', 'destinataire_id', 'objet', 'contenu', 'lu', 'date_envoi'];

    public function expediteur()
    {
        return $this->belongsTo(Utilisateur::class, 'expediteur_id', 'id_user');
    }

    public function destinataire()
    {
        return $this->belongsTo(Utilisateur::class, 'destinataire_id', 'id_user');
    }
}