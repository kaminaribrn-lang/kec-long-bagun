@extends('site.layout')
@section('judul', 'Data ' . $judul)

@php
    $pengganti = ['pendidikan' => 'Jumlah sekolah', 'kesehatan' => 'Jumlah puskesmas', 'ekonomi' => 'Data ekonomi'];
@endphp

@section('isi')
    @include('site.partials.phero', ['judul' => 'Data ' . Str::lower($judul), 'crumbs' => [['Data', route('data.index')], [$judul, null]]])
    <div class="page"><div class="in">
        <div class="grid" style="gap:28px">
            @if ($kampungs->isNotEmpty())
                @php $maks = max(1, $kampungs->max('penduduk')); @endphp
                <div class="card">
                    <div class="sh row" style="margin-bottom:16px"><h2 style="margin:0">Penduduk per kampung</h2><a class="btn alt sm" href="{{ route('data.csv', 'penduduk-per-kampung') }}">@include('site.partials.ikon', ['n' => 'unduh', 's' => 16])Unduh CSV</a></div>
                    <div class="chart" role="img" aria-label="Grafik batang penduduk per kampung">
                        @foreach ($kampungs as $k)
                            <div class="r"><span>{{ $k->nama }}</span><div class="tr"><div class="fl" style="width:{{ round($k->penduduk / $maks * 100, 1) }}%"></div></div><b>{{ Situs::angka($k->penduduk) }}</b></div>
                        @endforeach
                    </div>
                    <div class="tblw" style="margin-top:20px">
                        <table class="t">
                            <thead><tr><th>Kampung</th><th class="n">Luas (km²)</th><th class="n">Penduduk</th><th class="n">Kepadatan (jiwa/km²)</th><th class="n">RT</th></tr></thead>
                            <tbody>
                                @foreach ($kampungs as $k)
                                    <tr><td><a href="{{ route('kampung.show', $k->slug) }}" style="color:var(--g);font-weight:600">{{ $k->nama }}</a></td><td class="n">{{ Situs::angka($k->luas_km2, 2) }}</td><td class="n">{{ Situs::angka($k->penduduk) }}</td><td class="n">{{ Situs::angka($k->kepadatan, 1) }}</td><td class="n">{{ $k->jumlah_rt }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="src">Sumber: {{ $kampungs->first()->sumber_data }}, {{ $kampungs->first()->tahun_data }}.</p>
                </div>
            @endif

            @foreach ($kelompoks as $kunci => $baris)
                @php $maks = max(1, $baris->max('nilai')); $pertama = $baris->first(); @endphp
                <div class="card">
                    <div class="sh row" style="margin-bottom:16px"><h2 style="margin:0">{{ $labelKelompok[$kunci] ?? Str::headline((string) $kunci) }}</h2>@if ($kunci)<a class="btn alt sm" href="{{ route('data.csv', str_replace('_', '-', $kunci)) }}">@include('site.partials.ikon', ['n' => 'unduh', 's' => 16])Unduh CSV</a>@endif</div>
                    <div class="chart" role="img" aria-label="Grafik batang {{ $labelKelompok[$kunci] ?? $kunci }}">
                        @foreach ($baris as $s)
                            <div class="r"><span>{{ $s->label }}</span><div class="tr"><div class="fl" style="width:{{ round($s->nilai / $maks * 100, 1) }}%"></div></div><b>{{ Situs::angka($s->nilai) }}</b></div>
                        @endforeach
                    </div>
                    <div class="tblw" style="margin-top:20px">
                        <table class="t">
                            <thead><tr><th>Label</th><th class="n">Nilai</th><th>Tahun</th><th>Keterangan</th></tr></thead>
                            <tbody>
                                @foreach ($baris as $s)
                                    <tr><td>{{ $s->label }}</td><td class="n">{{ Situs::angka($s->nilai) }} {{ $s->satuan }}</td><td>{{ $s->tahun }}</td><td>{{ $s->keterangan }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="src">Sumber: @if ($pertama->tautan_sumber)<a href="{{ $pertama->tautan_sumber }}" target="_blank" rel="noopener">{{ $pertama->sumber }}</a>@else{{ $pertama->sumber }}@endif.</p>
                </div>
            @endforeach

            @if ($kelompoks->isEmpty() && $kampungs->isEmpty())
                <div class="card" style="text-align:center">
                    <p class="muted" style="margin:0">Data {{ Str::lower($judul) }} belum diisi: {!! Situs::isi(null, $pengganti[$topik] ?? 'Data') !!}. Angka akan bersumber dari Kecamatan Long Bagun Dalam Angka (BPS).</p>
                </div>
            @endif
        </div>
    </div></div>
@endsection
