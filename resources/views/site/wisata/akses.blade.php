@extends('site.layout')
@section('judul', 'Cara menuju Long Bagun')
@section('deskripsi', 'Jalur sungai, darat, dan udara menuju Kecamatan Long Bagun, Mahakam Ulu.')

@section('isi')
    @include('site.partials.phero', ['judul' => 'Cara menuju Long Bagun', 'lead' => 'Jalur sungai, darat, dan udara menuju Ujoh Bilang dan kampung-kampung di Long Bagun.', 'crumbs' => [['Wisata', route('wisata.index')], ['Cara menuju Long Bagun', null]]])
    <div class="page"><div class="in">
        <div class="grid g3">
            @forelse ($site->akses ?? [] as $jalur)
                <div class="card">
                    <span class="badge g" style="align-self:flex-start">Jalur</span>
                    <h2 style="margin-top:10px">{{ $jalur['jalur'] ?? '' }}</h2>
                    <p style="margin:0" class="muted">{{ $jalur['isi'] ?? '' }}</p>
                </div>
            @empty
                <div class="card"><p class="muted" style="margin:0">{!! Situs::isi(null, 'Informasi akses') !!}</p></div>
            @endforelse
        </div>
        @if (filled($site->akses_catatan))
            <div class="notice" style="background:var(--golds);border:1px solid #F1E3C4;color:#6B4A0A;margin-top:24px">{{ $site->akses_catatan }}</div>
        @endif
        <p class="src">Pembaruan terakhir: {!! Situs::isi($site->akses_diperbarui?->translatedFormat('j F Y'), 'Tanggal pembaruan') !!}. Kondisi jalur bisa berubah; hubungi kantor camat sebelum berangkat.</p>
    </div></div>
@endsection
