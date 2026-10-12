@extends('site.layout')
@section('judul', 'Data')
@section('deskripsi', 'Data dasar Kecamatan Long Bagun: kependudukan, pendidikan, kesehatan, dan ekonomi, dengan sumber dan tahun.')

@section('isi')
    @include('site.partials.phero', ['judul' => 'Data Kecamatan Long Bagun', 'lead' => 'Angka dasar wilayah, masing-masing dengan tahun dan sumbernya.', 'crumbs' => [['Data', null]]])
    <div class="page"><div class="in">
        @if ($kunci->isNotEmpty())
            <div class="grid g4" style="margin-bottom:32px">
                @foreach ($kunci as $s)
                    <div class="card">
                        <span class="small muted">{{ $s->label }}</span>
                        <div style="font-family:Montserrat,sans-serif;font-weight:700;font-size:28px;letter-spacing:-.02em;font-variant-numeric:tabular-nums">{{ Situs::angka($s->nilai) }} <span style="font-size:14px;font-weight:600;color:var(--ink2)">{{ $s->satuan }}</span></div>
                        <span class="src" style="margin:4px 0 0;display:block">{{ $s->sumber }}@if ($s->tahun), {{ $s->tahun }}@endif</span>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="grid g4">
            @foreach ($topiks as $slug => $nama)
                <a @class(['card', 'em' => $slug === 'kependudukan']) href="{{ route('data.topik', $slug) }}">
                    <h3 style="margin:0">{{ $nama }}</h3>
                    <p class="small muted" style="margin:0">{{ ($jumlah[$slug] ?? 0) > 0 || $slug === 'kependudukan' ? 'Tabel dan grafik tersedia' : 'Data belum diisi' }}</p>
                </a>
            @endforeach
        </div>
        <p class="src" style="margin-top:24px">Publikasi rujukan: <a href="https://mahulukab.bps.go.id/" target="_blank" rel="noopener">BPS Kabupaten Mahakam Ulu</a>.</p>
    </div></div>
@endsection
