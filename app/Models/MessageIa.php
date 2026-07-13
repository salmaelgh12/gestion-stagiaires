<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageIa extends Model
{
    protected $table = 'messages_ia';
    protected $primaryKey = 'id_message_ia';
    public $timestamps = false;
    protected $fillable = ['id_conversation', 'role', 'contenu', 'modele_utilise', 'date_envoi'];

    public function conversation()
    {
        return $this->belongsTo(ConversationIa::class, 'id_conversation', 'id_conversation');
    }

    public function modele()
    {
        return $this->belongsTo(ModeleIa::class, 'modele_utilise', 'id_modele');
    }
}