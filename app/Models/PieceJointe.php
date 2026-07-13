<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PieceJointe extends Model
{
    protected $table = 'pieces_jointes';
    protected $primaryKey = 'id_piece';
    protected $fillable = ['id_stagiaire', 'nom_fichier', 'chemin_fichier', 'type_document', 'date_upload', 'uploaded_by'];

    public function stagiaire()
    {
        return $this->belongsTo(Stagiaire::class, 'id_stagiaire', 'id_stagiaire');
    }

    public function uploader()
    {
        return $this->belongsTo(Utilisateur::class, 'uploaded_by', 'id_user');
    }
}