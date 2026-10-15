<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    use HasFactory;

    protected $fillable = [
        'montant',
        'description',
        'demande_id',
        'reponse_client',
    ];
    /**
     * Le devis appartient à une demande.
     */
    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }

    /**
     * Retourne le montant en euros.
     */
    public function getMontantFormattedAttribute()
    {
        return number_format($this->montant, 2, ',', ' ') . ' €';
    }

    /**
     * récupérer l’utilisateur lié au devis.
     */
    public function user()
    {
        return $this->demande ? $this->demande->user : null;
    }
}
