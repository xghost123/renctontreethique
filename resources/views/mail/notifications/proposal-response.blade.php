<x-mail::message>
# Proposal {{ ucfirst($data['status'] ?? 'responded') }}

Dear {{ $user->name }},

Your proposal has been **{{ ucfirst($data['status'] ?? 'responded') }}** by {{ $data['responder_name'] ?? 'a member' }}.

<x-mail::button :url="config('app.url') . '/home'">
View Details
</x-mail::button>

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
