@extends('site.layout')
@section('judul', 'Kampung')
@section('deskripsi', 'Daftar kampung di Kecamatan Long Bagun beserta luas, penduduk, dan jumlah RT.')

@section('isi')
    @include('site.partials.phero', ['judul' => 'Kampung di Kecamatan Long Bagun', 'lead' => 'Profil ' . $kampungs->count() . ' kampung: petinggi, luas wilayah, penduduk, dan potensinya.', 'crumbs' => [['Kampung', null]]])
    <div class="page"><div class="in">
        <div class="filters" role="group" aria-label="Urutkan">
            <span class="small muted">Urutkan:</span>
            <a href="{{ route('kampung.index') }}" @class(['on' => $urut === 'penduduk'])>Penduduk terbanyak</a>
            <a href="{{ route('kampung.index', ['urut' => 'nama']) }}" @class(['on' => $urut === 'nama'])>Nama A–Z</a>
        </div>
        <div class="grid g3">
            @foreach ($kampungs as $k)
                <a class="ncard" href="{{ route('kampung.show', $k->slug) }}">
                    <div class="img">@if (Situs::berkas($k->foto_utama))@include('site.partials.foto', ['path' => $k->foto_utama, 'label' => 'Kampung ' . $k->nama, 'alt' => $k->foto_alt ?: 'Kampung ' . $k->nama])@else @include('site.partials.peta-mini', ['kode' => $k->kode_wilayah, 'nama' => $k->nama])@endif</div>
                    <div class="body">
                        <span class="small muted">Kode wilayah {{ $k->kode_wilayah }}</span>
                        <h3>{{ $k->nama }}</h3>
                        <dl class="facts">
                            <div><dt>Penduduk {{ $k->tahun_data }}</dt><dd>{{ Situs::angka($k->penduduk) }} jiwa</dd></div>
                            <div><dt>Luas</dt><dd>{{ Situs::angka($k->luas_km2, 2) }} km²</dd></div>
                            <div><dt>RT</dt><dd>{{ $k->jumlah_rt }}</dd></div>
                        </dl>
                    </div>
                </a>
            @endforeach
        </div>
        @if ($kampungs->isNotEmpty())<p class="src">Sumber angka: {{ $kampungs->first()->sumber_data }}, {{ $kampungs->first()->tahun_data }}.</p>@endif
    </div></div>
@endsection
