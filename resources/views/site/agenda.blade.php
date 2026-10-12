@extends('site.layout')
@section('judul', 'Agenda')

@section('isi')
    @include('site.partials.phero', ['judul' => 'Agenda kegiatan', 'lead' => 'Jadwal kegiatan Kantor Camat Long Bagun. Acara yang sudah lewat pindah ke arsip.', 'crumbs' => [['Informasi', null], ['Agenda', null]]])
    <div class="page"><div class="in">
        <h2 class="sec-h" style="font-size:20px;margin:0 0 14px">Mendatang</h2>
        <div class="rows">
            @forelse ($mendatang as $a)
                <div class="rowi">
                    <div class="when"><b>{{ $a->schedule->format('j') }}</b><span>{{ $a->schedule->translatedFormat('M Y') }}</span></div>
                    <div class="grow">
                        <h3>{{ $a->caption }}</h3>
                        <span class="small muted">{{ $a->schedule->translatedFormat('l, j F Y') }}@if ($a->end_at && ! $a->end_at->isSameDay($a->schedule)) sampai {{ $a->end_at->translatedFormat('j F Y') }}@endif @if ($a->location) · {{ $a->location }}@endif</span>
                        @if ($a->description)<p class="small" style="margin:6px 0 0">{{ $a->description }}</p>@endif
                    </div>
                </div>
            @empty
                <div class="card" style="text-align:center"><p class="muted" style="margin:0">Belum ada agenda mendatang.</p></div>
            @endforelse
        </div>

        @if ($arsip->isNotEmpty())
            <h2 style="font-size:20px;margin:36px 0 14px">Arsip</h2>
            <div class="rows">
                @foreach ($arsip as $a)
                    <div class="rowi" style="opacity:.8">
                        <div class="when" style="background:var(--soft);color:var(--ink2)"><b>{{ $a->schedule->format('j') }}</b><span>{{ $a->schedule->translatedFormat('M Y') }}</span></div>
                        <div class="grow"><h3>{{ $a->caption }}</h3><span class="small muted">{{ $a->schedule->translatedFormat('j F Y') }}@if ($a->end_at && ! $a->end_at->isSameDay($a->schedule)) sampai {{ $a->end_at->translatedFormat('j F Y') }}@endif @if ($a->location) · {{ $a->location }}@endif</span>@if ($a->description)<p class="small" style="margin:6px 0 0">{{ $a->description }}</p>@endif</div>
                    </div>
                @endforeach
            </div>
        @endif
    </div></div>
@endsection
