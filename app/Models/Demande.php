<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;//utilisatoin de belongsTo

    protected $fillable = [
        'user_id',
        'type_travaux',
        'description',
        'nom',
        'prenom',
        'email',
        'telephone',
        'adresse',
        'code_postal',
        'ville',
        'statut',
    ];

    /**
     * Relation : une demande appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : une demande peut avoir un seul devis.
     */
    public function devis()
    {
        return $this->hasOne(Devis::class);
    }

    /**
     * Relation : une demande peut avoir une intervention planifiée.
     */
    public function intervention()
    {
        return $this->hasOne(Intervention::class);
    }
}
