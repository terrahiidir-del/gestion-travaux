<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_id',
        'date',
        'heure',
        'technicien_id',
        'description_technicien',
        'description',
        'rapport'  
    ];

    /**
     * L'intervention appartient à une demande.
     */
    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }

    /**
     * L'intervention est affectée à un technicien.
     */
    public function technicien()
    {
        return $this->belongsTo(User::class, 'technicien_id');
    }
}
