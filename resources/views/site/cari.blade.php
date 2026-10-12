@extends('site.layout')
@section('judul', $q !== '' ? 'Hasil pencarian "' . $q . '"' : 'Cari')

@section('isi')
    @include('site.partials.phero', ['judul' => 'Cari di situs', 'lead' => 'Cari layanan, kampung, berita, pengumuman, destinasi, dan dokumen.', 'crumbs' => [['Cari', null]]])
    <div class="page"><div class="in">
        <form class="search-f" method="get" action="{{ route('cari') }}" role="search">
            <label class="sr-only" for="q">Kata kunci</label>
            <input type="search" id="q" name="q" value="{{ $q }}" placeholder="Misalnya: surat pindah" minlength="2" required>
            <button class="btn" type="submit">@include('site.partials.ikon', ['n' => 'cari', 's' => 18])Cari</button>
        </form>

        @if ($q !== '')
            <p class="muted" style="margin:0 0 16px">{{ $hasil->count() }} hasil untuk "{{ $q }}".</p>
            <div class="rows">
                @forelse ($hasil as [$jenis, $judul, $ringkasan, $url])
                    <a class="rowi" href="{{ $url }}">
                        <span class="badge g">{{ $jenis }}</span>
                        <div class="grow"><h3>{{ $judul }}</h3>@if ($ringkasan)<span class="small muted">{{ Str::limit($ringkasan, 140) }}</span>@endif</div>
                    </a>
                @empty
                    <div class="card" style="text-align:center"><p class="muted" style="margin:0">Tidak ada hasil. Coba kata kunci lain yang lebih pendek.</p></div>
                @endforelse
            </div>
        @endif
    </div></div>
@endsection
