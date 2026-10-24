<x-mail::message>
# {{ $customSubject }}

A message from **{{ $organizerName }}** regarding {{ $event->title }}:

{{ $customMessage }}

---
Thanks,<br>
The {{ $organizerName }} Team
</x-mail::message>
