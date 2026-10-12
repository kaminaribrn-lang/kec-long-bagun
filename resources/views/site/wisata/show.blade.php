@extends('site.layout')
@section('judul', $destinasi->nama)
@section('deskripsi', Str::limit($destinasi->uraian, 155))
@if ($gambar = Situs::berkas($destinasi->foto_utama))
    @section('gambar', url($gambar))
@endif

@section('isi')
    @include('site.partials.phero', ['judul' => $destinasi->nama, 'lead' => $destinasi->jenis_label . ($destinasi->lokasi ? ' · ' . $destinasi->lokasi : ''), 'crumbs' => [['Wisata', route('wisata.index')], [$destinasi->nama, null]]])
    <div class="page"><div class="in">
        <div class="split">
            <div class="grid" style="gap:24px">
                <div>
                    <div class="img wide" style="margin:0">@include('site.partials.foto', ['path' => $destinasi->foto_utama, 'label' => $destinasi->nama, 'alt' => $destinasi->foto_alt ?: $destinasi->nama])</div>
                    @if ($destinasi->foto_utama)<p class="kredit">Foto: {!! Situs::isi($destinasi->foto_kredit, 'Kredit foto') !!}</p>@endif
                </div>
                <div class="card"><h2>Tentang</h2><div class="prose"><p>{{ $destinasi->uraian }}</p></div></div>
                <div class="card"><h2>Cara mencapai</h2><div class="prose">
                    @if (filled($destinasi->cara_mencapai)){!! nl2br(e($destinasi->cara_mencapai)) !!}@else<p>{!! Situs::isi(null, 'Cara mencapai') !!}</p>@endif
                    <p><a href="{{ route('wisata.akses') }}">Lihat cara menuju Long Bagun</a></p>
                </div></div>
                @if (filled($destinasi->galeri))
                    <div class="card"><h2>Galeri</h2>
                        <div class="galeri">
                            @foreach ($destinasi->galeri as $foto)
                                @php $src = Situs::berkas(is_array($foto) ? ($foto['foto'] ?? null) : $foto); @endphp
                                @if ($src)<figure><img src="{{ $src }}" alt="Foto {{ $destinasi->nama }}" loading="lazy"></figure>@endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            <aside class="card em">
                <h3>Ringkasan</h3>
                <dl class="facts">
                    <div><dt>Jenis</dt><dd>{{ $destinasi->jenis_label }}</dd></div>
                    <div><dt>Lokasi</dt><dd>{!! Situs::isi($destinasi->lokasi, 'Lokasi') !!}</dd></div>
                    @if ($destinasi->kampung)
                        <div><dt>Kampung</dt><dd><a href="{{ route('kampung.show', $destinasi->kampung->slug) }}" style="color:var(--g)">{{ $destinasi->kampung->nama }}</a></dd></div>
                    @endif
                    <div><dt>Waktu terbaik</dt><dd>{!! Situs::isi($destinasi->waktu_terbaik, 'Waktu terbaik') !!}</dd></div>
                </dl>
            </aside>
        </div>
    </div></div>
@endsection
