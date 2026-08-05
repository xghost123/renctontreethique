<x-mail::message>
# New Message

Dear {{ $user->name }},

You have received a new message from **{{ $data['sender_name'] ?? 'a member' }}**.

<x-mail::button :url="config('app.url') . '/messages'">
View Message
</x-mail::button>

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
