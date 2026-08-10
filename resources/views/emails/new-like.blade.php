@component('mail::message')
# Quelqu'un a aimé votre profil! 💕

Bonjour {{ $recipient->name }},

Bonne nouvelle! {{ $liker->name }} a aimé votre profil. Voulez-vous lui donner une chance?

---

### À propos de {{ $liker->name }}

- **Nom:** {{ $liker->name }}
- **Âge:** {{ $liker->age ?? 'Non spécifié' }} ans
- **Localisation:** {{ $liker->location ?? 'Non spécifiée' }}

@component('mail::button', ['url' => route('biodata.show', $liker->biodata?->id)])
Voir le profil complet
@endcomponent

## Qu'allez-vous faire?

Vous pouvez:
- ✓ Liker en retour - Engagez une connexion
- ✓ Voir le profil - Découvrez plus à son sujet
- ✓ Ignorer - Aucun problème, pas d'action nécessaire

---

### Conseils de Rencontre Éthique

Donnez-vous du temps pour bien connaître quelqu'un avant de prendre des décisions. Une connexion sincère vaut mieux qu'une connexion rapide.

Les profils approuvés par notre équipe sont tous de haute qualité et authenticité.

@component('mail::subcopy')
[Modifier les préférences d'email]({{ route('user.settings') }}) | [Se désabonner]({{ route('unsubscribe') }})
@endcomponent

@endcomponent
