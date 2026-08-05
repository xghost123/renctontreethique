<x-mail::message>
# Notification from Rencontre Éthique

Dear {{ $user->name }},

You have a new notification from Rencontre Éthique.

<x-mail::button :url="config('app.url') . '/home'">
View Notification
</x-mail::button>

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
