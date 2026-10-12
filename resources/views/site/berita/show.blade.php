@extends('site.layout')
@php
    $pengumuman = $jenis === 'pengumuman';
    $induk = $pengumuman ? ['Pengumuman', route('pengumuman.index')] : ['Berita', route('berita.index')];
    $rute = $pengumuman ? 'pengumuman.show' : 'berita.show';
    $foto = Situs::berkas($post->file?->storage_path ?? $post->file?->path);
    $ringkasan = $post->ringkasan ?: Str::limit(strip_tags((string) $post->content), 155);
    $tanggal = $post->published_at ?? $post->created_at;
    $tautan = urlencode(url()->current());
@endphp
@section('judul', $post->seo['meta_title'] ?? $post->title)
@section('deskripsi', $post->seo['meta_description'] ?? $ringkasan)
@section('og_type', 'article')
@if ($foto)
    @section('gambar', url($foto))
@endif

@push('head')
<script type="application/ld+json">{!! json_encode(array_filter([
    '@context' => 'https://schema.org',
    '@type' => 'NewsArticle',
    'headline' => $post->title,
    'datePublished' => optional($tanggal)->toIso8601String(),
    'dateModified' => optional($post->updated_at)->toIso8601String(),
    'image' => $foto ? url($foto) : null,
    'author' => ['@type' => 'Organization', 'name' => $post->penulis ?: 'Kantor Camat Long Bagun'],
    'publisher' => ['@type' => 'GovernmentOrganization', 'name' => 'Kantor Camat Long Bagun'],
]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('isi')
    @include('site.partials.phero', ['judul' => $post->title, 'crumbs' => [['Informasi', null], $induk]])
    <div class="page tight"><div class="in">
        <div class="split">
            <article>
                <div class="meta" style="margin-bottom:16px">
                    @if ($post->category)<span class="cat">{{ $post->category->name }}</span>@endif
                    <span>{{ optional($tanggal)->translatedFormat('j F Y') }}</span>
                    @if ($post->penulis)<span>Penulis: {{ $post->penulis }}</span>@endif
                    @if ($pengumuman && $post->berakhir_at)<span>Berlaku sampai {{ $post->berakhir_at->translatedFormat('j F Y') }}</span>@endif
                </div>
                @if ($foto || ! $pengumuman)
                    <div class="img wide">@include('site.partials.foto', ['path' => $post->file?->storage_path ?? $post->file?->path, 'label' => Str::limit($post->title, 40), 'alt' => $post->title])</div>
                    @if ($foto)<p class="kredit">Foto: {!! Situs::isi($post->kredit_foto, 'Kredit foto') !!}</p>@endif
                @endif
                <div class="prose" style="margin-top:22px">{{ Situs::konten($post->content) }}</div>

                <div class="sharebar">
                    <span>Bagikan:</span>
                    <a class="btn alt sm" href="https://wa.me/?text={{ urlencode($post->title . ' ') }}{{ $tautan }}" target="_blank" rel="noopener">WhatsApp</a>
                    <a class="btn alt sm" href="https://www.facebook.com/sharer/sharer.php?u={{ $tautan }}" target="_blank" rel="noopener">Facebook</a>
                </div>
            </article>
            <aside class="card">
                <h3>{{ $pengumuman ? 'Pengumuman lain' : 'Berita lain' }}</h3>
                <div class="rows">
                    @forelse ($lainnya as $l)
                        <a class="rowi" style="padding:12px 14px" href="{{ route($rute, $l->slug) }}"><div class="grow" style="min-width:0"><h3>{{ $l->title }}</h3><span class="small muted">{{ optional($l->published_at)->translatedFormat('j F Y') }}</span></div></a>
                    @empty
                        <p class="muted small" style="margin:0">Belum ada {{ $pengumuman ? 'pengumuman' : 'berita' }} lain.</p>
                    @endforelse
                </div>
            </aside>
        </div>
    </div></div>
@endsection
