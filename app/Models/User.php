<?php

namespace App\Models;

// ⛔️ On enlève MustVerifyEmail
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable // ⛔️ On enlève "implements MustVerifyEmail"
{
    use HasFactory, Notifiable;

    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prenom',
        'name',
        'email',
        'telephone',
        'adresse_postale',
        'code_postal',
        'ville',
        'password',
        'role',
    ];

    /**
     * Les attributs à masquer pour la sérialisation.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * technicien peut avoir plusieurs disponibilités.
     */
    public function disponibilites()
    {
        return $this->hasMany(Disponibilite::class, 'user_id');
    }
}
