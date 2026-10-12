@extends('site.layout')
@section('judul', 'Galeri')

@section('isi')
    @include('site.partials.phero', ['judul' => 'Galeri foto', 'lead' => 'Dokumentasi kegiatan di Kecamatan Long Bagun.', 'crumbs' => [['Informasi', null], ['Galeri', null]]])
    <div class="page"><div class="in">
        @forelse ($kelompoks as $kegiatan => $fotos)
            <h2 style="font-size:20px;margin:0 0 14px">{{ $kegiatan }}</h2>
            <div class="galeri" style="margin-bottom:36px">
                @foreach ($fotos as $f)
                    @if ($src = Situs::berkas($f->image))
                        <figure>
                            <img src="{{ $src }}" alt="{{ $f->title }}" loading="lazy" decoding="async">
                            <figcaption><b>{{ $f->title }}</b>@if ($f->description)<br><span class="muted">{{ $f->description }}</span>@endif<br><span class="muted">Foto: {!! Situs::isi($f->kredit, 'Kredit foto') !!}</span></figcaption>
                        </figure>
                    @endif
                @endforeach
            </div>
        @empty
            <div class="card" style="text-align:center"><p class="muted" style="margin:0">Belum ada foto. Foto yang diunggah admin tampil di sini, dikelompokkan per kegiatan.</p></div>
        @endforelse
    </div></div>
@endsection
