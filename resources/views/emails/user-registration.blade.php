@component('mail::message')
# Bienvenue sur Rencontre Éthique

Bonjour {{ $user->name }},

Merci de vous être inscrit(e) sur **Rencontre Éthique**, la plateforme matrimoniale halal réservée à la communauté musulmane. Nous sommes honorés de vous accueillir dans notre communauté.

## Prochaines étapes

1. **Complétez votre profil** - Remplissez votre biodata pour être visible aux autres membres
2. **Téléchargez une photo** - Une photo professionnelle augmente vos chances de correspondances
3. **Configurez vos préférences** - Indiquez vos critères de recherche

@component('mail::button', ['url' => route('profile.edit')])
Compléter mon profil
@endcomponent

## À propos de Rencontre Éthique

Nous nous engageons à créer un espace sûr et respectueux pour trouver votre partenaire selon les principes islamiques.

**Nos valeurs :**
- Respect et confiance
- Transparence absolue
- Modestie et dignité
- Protection de la vie privée

Merci de lire nos [Conditions d'utilisation]({{ config('app.url') }}/terms) et [Politique de confidentialité]({{ config('app.url') }}/privacy).

---

Besoin d'aide? Contactez notre équipe support à [support@rencontreeethique.com](mailto:support@rencontreeethique.com)

@component('mail::subcopy')
[Modifier les préférences d'email]({{ route('user.settings') }}) | [Se désabonner]({{ route('unsubscribe') }})
@endcomponent

@endcomponent
