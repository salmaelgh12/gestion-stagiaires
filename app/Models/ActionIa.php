<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionIa extends Model
{
    protected $table = 'actions_ia';
    protected $primaryKey = 'id_action';
    public $timestamps = false;
    protected $fillable = ['id_conversation', 'id_user', 'type_action', 'id_message', 'date_action'];

    public function conversation()
    {
        return $this->belongsTo(ConversationIa::class, 'id_conversation', 'id_conversation');
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_user', 'id_user');
    }

    public function message()
    {
        return $this->belongsTo(Message::class, 'id_message', 'id_message');
    }
}