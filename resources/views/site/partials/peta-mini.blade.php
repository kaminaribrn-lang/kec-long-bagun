{{--
    Peta mini letak sebuah kampung di Kecamatan Long Bagun, dipakai mengisi slot foto yang masih kosong.
    Bentuk wilayah sama dengan site.partials.peta (BIG, delineasi batas desa 2018, indikatif).
    Parameter: $kode (kode wilayah kampung), $nama.
--}}
@php
    $peta = \Illuminate\Support\Facades\Cache::rememberForever(
        'peta-long-bagun-' . filemtime(resource_path('data/peta-long-bagun.json')),
        fn () => json_decode(file_get_contents(resource_path('data/peta-long-bagun.json')), true)
    );
    $ini = $peta['d'][$kode] ?? null;
@endphp
<div class="pmini">
    <svg viewBox="0 0 {{ $peta['w'] }} {{ $peta['h'] }}" preserveAspectRatio="xMidYMid meet" role="img" aria-label="Peta letak Kampung {{ $nama }} di Kecamatan Long Bagun">
        @foreach ($peta['d'] as $k => $w)
            @continue($k === $kode)
            <path class="ds" d="{{ $w['p'] }}"/>
        @endforeach
        @if ($ini)<path class="ds on" d="{{ $ini['p'] }}"/>@endif
        <path class="rv" d="{{ $peta['riv'] }}"/>
        @if ($ini)
            <circle class="cincin" cx="{{ $ini['c'][0] }}" cy="{{ $ini['c'][1] }}" r="26"/>
            <circle class="titik" cx="{{ $ini['c'][0] }}" cy="{{ $ini['c'][1] }}" r="6"/>
        @endif
    </svg>
    <span class="pket">Peta letak</span>
</div>
