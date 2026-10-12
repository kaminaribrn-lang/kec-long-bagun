@extends('site.layout')
@php
    $pengumuman = $jenis === 'pengumuman';
    $judul = $pengumuman ? 'Pengumuman' : 'Berita';
    $rute = $pengumuman ? 'pengumuman.show' : 'berita.show';
@endphp
@section('judul', $judul)

@section('isi')
    @include('site.partials.phero', ['judul' => $judul, 'lead' => $pengumuman ? 'Pengumuman resmi Kantor Camat Long Bagun.' : 'Kabar kegiatan dan informasi dari Kecamatan Long Bagun.', 'crumbs' => [['Informasi', null], [$judul, null]]])
    <div class="page"><div class="in">
        <div class="filters" role="group" aria-label="Jenis informasi">
            <a href="{{ route('berita.index') }}" @class(['on' => ! $pengumuman && ! $kategori])>Semua berita</a>
            @foreach ($kategoris as $k)
                <a href="{{ route('berita.index', ['kategori' => $k->slug]) }}" @class(['on' => $kategori === $k->slug])>{{ $k->name }}</a>
            @endforeach
            <a href="{{ route('pengumuman.index') }}" @class(['on' => $pengumuman])>Pengumuman</a>
        </div>

        <div class="grid gberita">
            @forelse ($posts as $p)
                <a class="ncard" href="{{ route($rute, $p->slug) }}">
                    <div class="img">@include('site.partials.foto', ['path' => $p->file?->storage_path ?? $p->file?->path, 'label' => Str::limit($p->title, 36), 'alt' => $p->title])</div>
                    <div class="body">
                        <div class="meta">@if ($p->category)<span class="cat">{{ $p->category->name }}</span>@endif<span>{{ optional($p->published_at ?? $p->created_at)->translatedFormat('j F Y') }}</span></div>
                        <h3>{{ $p->title }}</h3>
                        <p>{{ $p->ringkasan ?: Str::limit(strip_tags((string) $p->content), 130) }}</p>
                    </div>
                </a>
            @empty
                <div class="card" style="grid-column:1/-1;text-align:center"><p class="muted" style="margin:0">Belum ada {{ Str::lower($judul) }}. {{ $judul }} yang diterbitkan admin tampil di sini.</p></div>
            @endforelse
        </div>

        {{ $posts->links('site.partials.pager') }}
    </div></div>
@endsection
