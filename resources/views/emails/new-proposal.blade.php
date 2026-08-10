@component('mail::message')
# Vous avez reçu une nouvelle proposition! 💌

Bonjour {{ $proposedUser->name }},

Une personne intéressante a envoyé une proposition pour vous! Découvrez qui c'est et décidez si vous souhaitez poursuivre.

## Quelqu'un est intéressé par votre profil

{{ $proposer->name }} a pensé que vous pourriez être compatible. Cette personne serait honorée de discuter avec vous.

@component('mail::button', ['url' => route('proposal.show', $proposal->id)])
Voir la proposition
@endcomponent

## Qu'est-ce que vous devez faire?

Vous pouvez:
- **Accepter** - Initiez une conversation
- **Refuser** - L'autre personne sera respectueusement informée
- **Réfléchir** - Prenez votre temps avant de décider

**Vous avez 30 jours pour répondre.**

---

### À propos du proposant

- **Nom:** {{ $proposer->name }}
- **Âge:** {{ $proposer->age ?? 'Non spécifié' }} ans
- **Localisation:** {{ $proposer->location ?? 'Non spécifiée' }}

Découvrez leur profil complet et leur biodata avant de décider.

---

Nous croyons en l'authenticité et le respect mutuel. Prenez votre temps pour connaître cette personne avant de prendre une décision.

@component('mail::subcopy')
[Modifier les préférences d'email]({{ route('user.settings') }}) | [Se désabonner]({{ route('unsubscribe') }})
@endcomponent

@endcomponent
