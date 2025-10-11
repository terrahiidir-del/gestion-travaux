<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class technicien extends Model
{
    use HasFactory;

    // Attributs qu'on peut remplir en masse
    protected $fillable = [
        'nom',
        'email',
        // ajoute ici d'autres champs si nécessaire
    ];

    // Relation : un technicien a plusieurs disponibilités
    public function disponibilites()
    {
        return $this->hasMany(Disponibilite::class);
    }
}
