@extends('site.layout')
@section('judul', 'Geografi dan peta')

@section('isi')
    @include('site.partials.phero', ['judul' => 'Geografi dan peta', 'crumbs' => [['Profil', null], ['Geografi dan peta', null]]])
    <div class="page"><div class="in">
            <div class="split">
            <div>
                <div class="prose">
                    @if (filled($site->geografi))
                        {!! $site->geografi !!}
                    @else
                        <p>Kecamatan Long Bagun terletak di bagian hulu Sungai Mahakam, Kabupaten Mahakam Ulu, Kalimantan Timur. Ibu kota kecamatan berada di Kampung Ujoh Bilang, yang juga ibu kota kabupaten.</p>
                    @endif
                </div>
                <div class="card" style="margin-top:24px">
                    <h3>Peta Kecamatan Long Bagun</h3>
                    <p class="src" style="margin:0 0 12px">Batas kampung dari BIG (delineasi batas desa 2018, bersifat indikatif). Klik wilayah untuk membuka profil kampung.</p>
                    <div style="display:flex;flex-direction:column;min-height:460px">@include('site.partials.peta', ['kampungs' => $kampungs, 'terpilih' => null])</div>
                </div>
            </div>
            <aside class="card">
                <h3>Fakta wilayah</h3>
                <dl class="facts">
                    @foreach ($kunci as $s)
                        <div><dt>{{ $s->label }}</dt><dd>{{ Situs::angka($s->nilai) }} {{ $s->satuan }}</dd></div>
                    @endforeach
                    <div><dt>Kode wilayah</dt><dd>64.11.01</dd></div>
                    <div><dt>Kode pos</dt><dd>75767</dd></div>
                    @if ($site->latitude && $site->longitude)
                        <div><dt>Koordinat kantor</dt><dd>{{ Situs::angka($site->latitude, 4) }}; {{ Situs::angka($site->longitude, 4) }}</dd></div>
                    @endif
                    <div><dt>Zona waktu</dt><dd>WITA (UTC+8)</dd></div>
                    <div><dt>Batas utara</dt><dd>{!! Situs::isi($site->batas_utara, 'Batas utara') !!}</dd></div>
                    <div><dt>Batas timur</dt><dd>{!! Situs::isi($site->batas_timur, 'Batas timur') !!}</dd></div>
                    <div><dt>Batas selatan</dt><dd>{!! Situs::isi($site->batas_selatan, 'Batas selatan') !!}</dd></div>
                    <div><dt>Batas barat</dt><dd>{!! Situs::isi($site->batas_barat, 'Batas barat') !!}</dd></div>
                </dl>
                @if ($kunci->isNotEmpty())<p class="src">Sumber: {{ $kunci->first()->sumber }}</p>@endif
            </aside>
        </div>
    </div></div>
@endsection
