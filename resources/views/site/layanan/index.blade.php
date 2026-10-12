@extends('site.layout')
@section('judul', 'Layanan')
@section('deskripsi', 'Daftar layanan Kantor Camat Long Bagun beserta syarat, alur, waktu penyelesaian, dan biaya.')

@section('isi')
    @include('site.partials.phero', ['judul' => 'Layanan Kantor Camat', 'lead' => 'Cek syarat dan alur tiap layanan sebelum datang ke kantor.', 'crumbs' => [['Layanan', null]]])
    <div class="page"><div class="in">
        @if ($kategoris->isNotEmpty())
            <div class="filters" role="group" aria-label="Saring kategori">
                <a href="{{ route('layanan.index') }}" @class(['on' => ! $kategori])>Semua</a>
                @foreach ($kategoris as $k)
                    <a href="{{ route('layanan.index', ['kategori' => $k]) }}" @class(['on' => $kategori === $k])>{{ $k }}</a>
                @endforeach
            </div>
        @endif

        <div class="grid g3">
            @forelse ($layanans as $l)
                <a class="card" href="{{ route('layanan.show', $l->slug) }}">
                    <span class="chip" style="width:44px;height:44px;border-radius:14px;display:grid;place-items:center;background:var(--gs);color:var(--g)">@include('site.partials.ikon', ['n' => $l->ikon])</span>
                    @if ($l->kategori)<span class="badge g" style="align-self:flex-start">{{ $l->kategori }}</span>@endif
                    <h3 style="margin:0">{{ $l->nama }}</h3>
                    <p class="muted small" style="margin:0">{{ $l->ringkasan ?: 'Lihat syarat, alur, waktu penyelesaian, dan biaya.' }}</p>
                </a>
            @empty
                <div class="card"><p class="muted" style="margin:0">Belum ada layanan pada kategori ini.</p></div>
            @endforelse
        </div>

        <div class="grid g2" style="margin-top:28px">
            <a class="card em" href="{{ route('layanan.formulir') }}"><h3>Unduh formulir</h3><p class="muted small" style="margin:0">Formulir layanan yang bisa diisi sebelum datang ke kantor.</p></a>
            <a class="card" href="{{ route('layanan.pengaduan') }}" style="background:linear-gradient(120deg,var(--golds),#fff 65%)"><h3>Pengaduan warga</h3><p class="muted small" style="margin:0">Sampaikan keluhan atau masukan kepada kantor camat.</p></a>
        </div>
    </div></div>
@endsection
