{{-- Kepala halaman dalam. Bila $halaman (Halaman Dinamis) ada, judul, pengantar, dan lencananya dipakai. --}}
@php
    $h = $halaman ?? null;
    $judulTampil = $h?->title ?: $judul;
    $leadTampil = data_get($h?->content, 'subtitle') ?: ($lead ?? null);
    $jejak = $crumbs ?? [];
    if ($h && $jejak) {
        $jejak[array_key_last($jejak)][0] = $judulTampil;
    }
@endphp
<section class="phero">
    <div class="orn orn-p" aria-hidden="true"></div>
    <div class="in">
        <nav class="crumb" aria-label="Jejak halaman">
            <a href="{{ route('beranda') }}">Beranda</a>
            @foreach ($jejak as [$teks, $url])
                <span>@if ($url)<a href="{{ $url }}">{{ $teks }}</a>@else{{ $teks }}@endif</span>
            @endforeach
        </nav>
        @if (filled($h?->hero_badge))<span class="pill" style="margin-bottom:12px"><i></i>{{ $h->hero_badge }}</span>@endif
        <h1>{{ $judulTampil }}</h1>
        @if (filled($leadTampil))<p class="lead">{{ $leadTampil }}</p>@endif
    </div>
</section>
