<x-mail::message>
# New Trainer Request Application

You have received a new application through the "Be a Trainer" form.

**Applicant Details:**
- **Name:** {{ $trainerRequest->name }}
- **Email:** {{ $trainerRequest->email }}
- **Phone:** {{ $trainerRequest->phone_number }}
- **Country:** {{ $trainerRequest->country }}

**What they can help with:**
<div style="background-color: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb; margin-top: 10px;">
{!! $trainerRequest->help_topic !!}
</div>

<x-mail::button :url="route('admin.trainers.show', $trainerRequest->id)">
View Full Application
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
