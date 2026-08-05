<x-mail::message>
# Profile Approved

Dear {{ $user->name }},

Congratulations! Your profile has been **approved** by the Rencontre Éthique admin team.

You can now start browsing and connecting with other members.

<x-mail::button :url="config('app.url') . '/home'">
Get Started
</x-mail::button>

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
