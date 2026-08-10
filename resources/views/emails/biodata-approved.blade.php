@component('mail::message')
# Votre profil est approuvé! 🎉

Bonjour {{ $user->name }},

Excellente nouvelle! Votre biodata a été **approuvé(e)** par notre équipe de modération. Vous êtes maintenant visible à tous les autres membres de Rencontre Éthique.

## Vous êtes maintenant actif(ve)!

Votre profil est maintenant complet et visible. Vous pouvez:

- ✓ Recevoir et envoyer des propositions
- ✓ Voir les autres profils
- ✓ Envoyer des messages
- ✓ Liker des profils

@component('mail::button', ['url' => route('biodata.show', $biodata->id)])
Voir mon profil
@endcomponent

## Conseils pour réussir

1. **Attendez les bonnes propositions** - La qualité prime sur la quantité
2. **Soyez patient(e)** - Trouver la bonne personne prend du temps
3. **Restez honnête** - L'authenticité est la clé du succès

---

Vous avez des questions? Notre équipe est là pour vous aider.

[Contacter le support](mailto:support@rencontreethique.com)

@component('mail::subcopy')
[Modifier les préférences d'email]({{ route('user.settings') }}) | [Se désabonner]({{ route('unsubscribe') }})
@endcomponent

@endcomponent
