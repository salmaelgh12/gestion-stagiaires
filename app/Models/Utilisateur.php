<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Utilisateur extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'utilisateurs';
    protected $primaryKey = 'id_user';
    protected $fillable = ['nom', 'prenom', 'email', 'telephone', 'mot_de_passe', 'id_role', 'actif', 'photo'];
    protected $hidden = ['mot_de_passe'];

    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }

    public function stagiaire()
    {
        return $this->hasOne(Stagiaire::class, 'id_user', 'id_user');
    }

    public function tachesEncadrees()
    {
        return $this->hasMany(Tache::class, 'id_encadrant', 'id_user');
    }

    public function messagesEnvoyes()
    {
        return $this->hasMany(Message::class, 'expediteur_id', 'id_user');
    }

    public function messagesRecus()
    {
        return $this->hasMany(Message::class, 'destinataire_id', 'id_user');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'id_user', 'id_user');
    }

    public function conversationsIa()
    {
        return $this->hasMany(ConversationIa::class, 'id_user', 'id_user');
    }
}