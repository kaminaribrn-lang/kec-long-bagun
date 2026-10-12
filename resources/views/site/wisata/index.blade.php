@extends('site.layout')
@section('judul', 'Potensi dan wisata')
@section('deskripsi', 'Destinasi alam, budaya, dan acara adat di Kecamatan Long Bagun, hulu Sungai Mahakam.')

@section('isi')
    @include('site.partials.phero', ['judul' => 'Potensi dan wisata', 'lead' => 'Destinasi alam, budaya, dan acara adat di hulu Sungai Mahakam.', 'crumbs' => [['Wisata', null]]])
    <div class="page"><div class="in">
        <div class="filters" role="group" aria-label="Saring jenis">
            <a href="{{ route('wisata.index') }}" @class(['on' => ! isset($jenisList[$jenis])])>Semua</a>
            @foreach ($jenisList as $kunci => $nama)
                <a href="{{ route('wisata.index', ['jenis' => $kunci]) }}" @class(['on' => $jenis === $kunci])>{{ $nama }}</a>
            @endforeach
            <a href="{{ route('wisata.akses') }}" style="margin-left:auto;border-color:var(--g2);color:var(--g)">Cara menuju Long Bagun</a>
        </div>
        <div class="wis" style="grid-template-columns:repeat(auto-fit,minmax(min(100%,300px),1fr))">
            @forelse ($destinasis as $d)
                <a href="{{ route('wisata.show', $d->slug) }}" class="wc">
                    <div class="img">@include('site.partials.foto', ['path' => $d->foto_utama, 'label' => $d->nama, 'alt' => $d->foto_alt ?: $d->nama])</div>
                    <div class="body"><span class="jn">{{ $d->jenis_label }}@if ($d->lokasi) · {{ $d->lokasi }}@endif</span><h3>{{ $d->nama }}</h3><p>{{ Str::limit($d->uraian, 130) }}</p></div>
                </a>
            @empty
                <div class="card"><p class="muted" style="margin:0">Belum ada destinasi pada jenis ini.</p></div>
            @endforelse
        </div>
    </div></div>
@endsection
