@foreach ($participants as $participant)
**{{ $participant->name }}** \
{{ $participant->street }} \
{{ $participant->zip_code }} {{ $participant->city }} {{ ($participant->email || $participant->phone) ? '\\' : '' }}
{{ collect([$participant->email, $participant->phone])->filter()->join(' / ') }}

@endforeach
