@component('mail::message')
# Vous avez reçu un nouveau message 💬

Bonjour {{ $recipient->name }},

{{ $sender->name }} vous a envoyé un message. Découvrez ce qu'il/elle a à vous dire!

---

**Extrait du message:**

> {{ Str::limit($message->message, 150) }}

@component('mail::button', ['url' => route('message.show', $message->id)])
Lire le message complet
@endcomponent

## Répondre

Cliquez sur le bouton ci-dessus pour lire le message complet et répondre. Votre réponse rapide montre votre intérêt et peut mener à une belle conversation.

### À propos du message

- **De:** {{ $sender->name }}
- **Envoyé:** {{ $message->created_at->diffForHumans() }}
- **Sujet:** Discussion matrimoniale

---

Bienvenue dans la conversation! Prenez votre temps pour répondre et connaître cette personne.

@component('mail::subcopy')
[Modifier les préférences d'email]({{ route('user.settings') }}) | [Se désabonner]({{ route('unsubscribe') }})
@endcomponent

@endcomponent
