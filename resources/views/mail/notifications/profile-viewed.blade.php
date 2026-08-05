<x-mail::message>
# Your Profile was Viewed

Dear {{ $user->name }},

{{ $data['viewer_name'] ?? 'Someone' }} viewed your profile on Rencontre Éthique.

<x-mail::button :url="config('app.url') . '/home'">
View Details
</x-mail::button>

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
