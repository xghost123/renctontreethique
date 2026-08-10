@component('mail::message')
# Votre proposition a été acceptée! 🎊

Bonjour {{ $proposer->name }},

Bonne nouvelle! {{ $proposedUser->name }} a accepté votre proposition de mariage. Ils seraient heureux de discuter avec vous.

## Prochaines étapes

Vous pouvez maintenant communiquer directement:

@component('mail::button', ['url' => route('message.conversation', $proposal->proposed_user_id)])
Envoyer un message
@endcomponent

## Conseils pour la conversation

1. **Soyez respectueux** - Montrez votre intérêt sincère
2. **Posez des questions pertinentes** - Connaissez mieux cette personne
3. **Respectez le rythme** - Chacun a son propre timing
4. **Restez honnête** - L'authenticité crée la confiance

### Profil de {{ $proposedUser->name }}

- **Nom:** {{ $proposedUser->name }}
- **Âge:** {{ $proposedUser->age ?? 'Non spécifié' }} ans
- **Localisation:** {{ $proposedUser->location ?? 'Non spécifiée' }}

---

Nous vous souhaitons le meilleur dans votre recherche. Que cette connexion vous rapproche de votre objectif de mariage dans le respect des valeurs islamiques.

**Bismi'Allah ar-Rahman ar-Rahim** ☪️

@component('mail::subcopy')
[Modifier les préférences d'email]({{ route('user.settings') }}) | [Se désabonner]({{ route('unsubscribe') }})
@endcomponent

@endcomponent
