<x-mail::message>
# You've been invited

@if ($inviterName)
{{ $inviterName }} has invited you to join the group **{{ $groupName }}** on {{ config('app.name') }}.
@else
You have been invited to join the group **{{ $groupName }}** on {{ config('app.name') }}.
@endif

Click the button below to choose a name and password and join the game.

<x-mail::button :url="$acceptUrl">
Accept invitation
</x-mail::button>

This invitation expires on {{ $expiresAt }}. If you weren't expecting it, you can safely ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
