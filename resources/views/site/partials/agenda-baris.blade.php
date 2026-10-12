{{-- Baris agenda ringkas untuk beranda. $lalu = true untuk agenda yang sudah lewat. --}}
@foreach ($items as $a)
    <a @class(['rowi', 'lalu' => $lalu ?? false]) href="{{ route('agenda') }}">
        <div class="when"><b>{{ $a->schedule->format('j') }}</b><span>{{ $a->schedule->translatedFormat('M') }}</span></div>
        <div class="grow"><h3>{{ $a->caption }}</h3><span class="small muted">{{ $a->schedule->translatedFormat(($lalu ?? false) ? 'j F Y' : 'H:i') }}@if ($a->location) · {{ $a->location }}@endif</span></div>
    </a>
@endforeach
