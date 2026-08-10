@component('mail::message')
# Votre profil nécessite des révisions

Bonjour {{ $user->name }},

Merci pour votre inscription et la soumission de votre biodata. Après examen, notre équipe de modération a identifié quelques points à améliorer avant approbation.

## Raison du refus

@if($feedback)
{{ $feedback }}
@else
Votre profil ne respecte pas entièrement nos directives de qualité et de clarté. Veuillez mettre à jour les informations manquantes ou inexactes.
@endif

## Comment corriger

1. Ouvrez votre profil
2. Mettez à jour les sections indiquées
3. Remplissez tous les champs requis clairement
4. Résoumettez pour approbation

@component('mail::button', ['url' => route('biodata.edit', $biodata->id)])
Modifier mon profil
@endcomponent

## Points d'attention

- Utilisez une photo claire et récente (visage visible)
- Décrivez-vous honnêtement et complètement
- Évitez les demandes incompatibles avec nos principes
- Vérifiez l'orthographe et la grammaire

**Notre équipe examinera votre profil dans les 24-48 heures après la résoumission.**

---

Des questions? Contactez notre support:

[support@rencontreeethique.com](mailto:support@rencontreeethique.com)

@component('mail::subcopy')
[Modifier les préférences d'email]({{ route('user.settings') }}) | [Se désabonner]({{ route('unsubscribe') }})
@endcomponent

@endcomponent
