<x-mail::message>
# New Trainer Request Application

You have received a new application through the "Be a Trainer" form.

**Applicant Details:**
- **Name:** {{ $trainerRequest->name }}
- **Email:** {{ $trainerRequest->email }}
- **Phone:** {{ $trainerRequest->phone_number }}
- **Country:** {{ $trainerRequest->country }}

@if($trainerRequest->program_type)
**Program Details:**
- **Type:** <span style="text-transform: capitalize;">{{ $trainerRequest->program_type }}</span>
- **Duration:** @if($trainerRequest->duration_days || $trainerRequest->duration_hours){{ $trainerRequest->duration_days ? $trainerRequest->duration_days . ' Days' : '' }}{{ $trainerRequest->duration_days && $trainerRequest->duration_hours ? ', ' : '' }}{{ $trainerRequest->duration_hours ? $trainerRequest->duration_hours . ' Hours' : '' }}@else - @endif
- **Free Provision:** {{ $trainerRequest->agreed_to_free_provision ? 'Yes, Agreed' : 'Not Agreed' }}

**Program Agenda:**
<div style="background-color: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb; margin-top: 10px;">
{!! $trainerRequest->agenda !!}
</div>
@else
**What they can help with:**
<div style="background-color: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb; margin-top: 10px;">
{!! $trainerRequest->getRawOriginal('help_topic') ?? $trainerRequest->help_topic !!}
</div>
@endif
<x-mail::button :url="route('admin.trainers.show', $trainerRequest->id)">
View Full Application
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
