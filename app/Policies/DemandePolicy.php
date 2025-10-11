<?php

namespace App\Policies;

use App\Models\Demande;
use App\Models\User;

class DemandePolicy
{
    //L'utilisateur peut modifier uniquement ses propres demandes.
     
    public function update(User $user, Demande $demande): bool
    {
        
        if ($user->role === 'admin') return true;

        // Propriétaire uniquement peut changer
        if ($user->id !== $demande->user_id) return false;

        if (method_exists($demande, 'devis') && $demande->devis()->exists()) {
            return false;
    }

        return true;
    }

    /**
     * L'utilisateur peut supprimer uniquement ses propres demandes.
     */
    public function delete(User $user, Demande $demande)
    {
        return $user->id === $demande->user_id;
    }
}
