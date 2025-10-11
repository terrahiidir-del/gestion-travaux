@component('mail::message')
# Intervention planifiée

Bonjour {{ $intervention->demande->prenom ?? 'Client' }},

Votre intervention a été planifiée :

- **Date** : {{ \Carbon\Carbon::parse($intervention->date)->format('d/m/Y') }}
- **Heure** : {{ $intervention->heure }}
- **Technicien** : {{ $intervention->technicien->name ?? 'À confirmer' }}

Nous restons à votre disposition pour toute question.

Merci pour votre confiance,  
L'équipe technique

@endcomponent
