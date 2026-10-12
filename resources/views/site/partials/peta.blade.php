{{--
    Peta Kecamatan Long Bagun dengan batas kampung.
    Bentuk wilayah: resources/data/peta-long-bagun.json (BIG, delineasi batas desa 2018, indikatif),
    dicocokkan ke koleksi Kampung lewat kode wilayah.
    Parameter: $kampungs (koleksi), $terpilih (slug atau null).
--}}
@php
    $peta = \Illuminate\Support\Facades\Cache::rememberForever(
        'peta-long-bagun-' . filemtime(resource_path('data/peta-long-bagun.json')),
        fn () => json_decode(file_get_contents(resource_path('data/peta-long-bagun.json')), true)
    );
    // Geser label kampung yang wilayahnya kecil agar tidak saling menimpa.
    $geser = ['64.11.01.2011' => [-34, -4], '64.11.01.2001' => [34, 18], '64.11.01.2004' => [4, 6], '64.11.01.2007' => [-22, 0], '64.11.01.2006' => [0, -8]];
    $terpilih ??= null;
@endphp
<div class="map">
    <svg viewBox="0 0 {{ $peta['w'] }} {{ $peta['h'] }}" role="img" aria-label="Peta Kecamatan Long Bagun dengan batas sebelas kampung">
        <defs>
            <linearGradient id="lbg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#34D399"/><stop offset="1" stop-color="#A7F3D0"/></linearGradient>
            <clipPath id="mclip"><rect width="{{ $peta['w'] }}" height="{{ $peta['h'] }}"/></clipPath>
        </defs>
        @foreach ($kampungs as $k)
            @continue(! isset($peta['d'][$k->kode_wilayah]))
            <a href="{{ route('kampung.show', $k->slug) }}" data-kampung="{{ $k->slug }}">
                <path @class(['ds', 'on' => $terpilih === $k->slug]) d="{{ $peta['d'][$k->kode_wilayah]['p'] }}"><title>{{ $k->nama }}</title></path>
            </a>
        @endforeach
        <path class="rv" clip-path="url(#mclip)" d="{{ $peta['riv'] }}"/>
        @foreach ($kampungs as $k)
            @continue(! isset($peta['d'][$k->kode_wilayah]))
            @php $c = $peta['d'][$k->kode_wilayah]['c']; $g = $geser[$k->kode_wilayah] ?? [0, 0]; @endphp
            <text @class(['on' => $terpilih === $k->slug]) data-kampung="{{ $k->slug }}" x="{{ $c[0] + $g[0] }}" y="{{ $c[1] + $g[1] }}">{{ $k->nama }}</text>
        @endforeach
        <circle class="kc" cx="{{ $peta['kc'][0] }}" cy="{{ $peta['kc'][1] }}" r="4.5"><title>Kantor camat</title></circle>
    </svg>
</div>
<p class="mleg">
    <span><i style="width:10px;height:10px;border-radius:50%;background:#F2B53A;border:1.5px solid #7A4A00"></i>Kantor camat</span>
    <span><i style="width:18px;height:2px;background:#0E9F9A"></i>Sungai Mahakam</span>
    <span><i style="width:12px;height:12px;border-radius:3px;background:#6EE7B7;border:1px solid #047857"></i>Kampung terpilih</span>
</p>
