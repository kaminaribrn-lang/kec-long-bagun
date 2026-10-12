@extends('site.layout')
@section('judul', 'Unduh formulir')

@section('isi')
    @include('site.partials.phero', ['judul' => 'Unduh formulir', 'lead' => 'Formulir layanan yang bisa diisi sebelum datang ke kantor camat.', 'crumbs' => [['Layanan', route('layanan.index')], ['Unduh formulir', null]]])
    <div class="page"><div class="in">
        <div class="rows">
            @forelse ($layanans as $l)
                <div class="rowi">
                    <div class="grow"><h3>Formulir {{ $l->nama }}</h3>@if ($l->kategori)<span class="small muted">{{ $l->kategori }}</span>@endif</div>
                    <a class="btn alt sm" href="{{ route('layanan.show', $l->slug) }}">Lihat syarat</a>
                    @if ($berkas = Situs::berkas($l->formulir))
                        <a class="btn sm" href="{{ $berkas }}" target="_blank" rel="noopener">@include('site.partials.ikon', ['n' => 'unduh', 's' => 16])Unduh</a>
                    @endif
                </div>
            @empty
                <div class="card" style="text-align:center"><p class="muted" style="margin:0">Belum ada formulir yang bisa diunduh. Formulir yang diunggah admin pada tiap layanan tampil di sini.</p></div>
            @endforelse
        </div>
    </div></div>
@endsection
