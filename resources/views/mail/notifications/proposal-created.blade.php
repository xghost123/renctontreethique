<x-mail::message>
# New Marriage Proposal

Dear {{ $user->name }},

You have received a new marriage proposal from **{{ $data['sender_name'] ?? 'a member' }}**.

<x-mail::button :url="config('app.url') . '/home'">
View Proposal
</x-mail::button>

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
