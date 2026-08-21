<x-mail::message>
# New Contact Enquiry

**From:** {{ $contactMessage->name }} ({{ $contactMessage->email }})
@if($contactMessage->phone)
**Phone:** {{ $contactMessage->phone }}
@endif
**Subject:** {{ $contactMessage->subject }}

{{ $contactMessage->message }}

<x-mail::button :url="route('admin.contact-messages.index')">
View in Admin
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
