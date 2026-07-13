<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversationIa extends Model
{
    protected $table = 'conversations_ia';
    protected $primaryKey = 'id_conversation';
    protected $fillable = ['id_user', 'titre', 'date_debut', 'date_derniere_activite'];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_user', 'id_user');
    }

    public function messagesIa()
    {
        return $this->hasMany(MessageIa::class, 'id_conversation', 'id_conversation');
    }

    public function actionsIa()
    {
        return $this->hasMany(ActionIa::class, 'id_conversation', 'id_conversation');
    }
}