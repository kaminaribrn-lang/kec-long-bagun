@extends('site.layout')
@section('judul', $layanan->nama)
@section('deskripsi', Str::limit($layanan->ringkasan ?: 'Syarat, alur, waktu penyelesaian, dan biaya layanan ' . $layanan->nama . ' di Kantor Camat Long Bagun.', 155))

@php
    $teks = fn ($butir) => is_array($butir) ? ($butir['teks'] ?? '') : $butir;
    $formulir = Situs::berkas($layanan->formulir);
@endphp

@section('isi')
    @include('site.partials.phero', ['judul' => $layanan->nama, 'lead' => $layanan->ringkasan, 'crumbs' => [['Layanan', route('layanan.index')], [$layanan->nama, null]]])
    <div class="page"><div class="in">
        <div class="split">
            <div class="grid" style="gap:20px">
                <div class="card">
                    <h2>Syarat</h2>
                    @if (filled($layanan->syarat))
                        <ul class="check">@foreach ($layanan->syarat as $s)<li>{{ $teks($s) }}</li>@endforeach</ul>
                    @else
                        <p style="margin:0">{!! Situs::isi(null, 'Syarat') !!}</p>
                    @endif
                </div>
                <div class="card">
                    <h2>Alur</h2>
                    @if (filled($layanan->alur))
                        <ol class="steps">@foreach ($layanan->alur as $a)<li><span>{{ $teks($a) }}</span></li>@endforeach</ol>
                    @else
                        <p style="margin:0">{!! Situs::isi(null, 'Alur') !!}</p>
                    @endif
                </div>
                @if (filled($layanan->dasar_hukum))
                    <div class="card">
                        <h2>Dasar hukum</h2>
                        <ul class="hukum">@foreach (preg_split('/\R+/', trim($layanan->dasar_hukum)) as $h)<li>{{ $h }}</li>@endforeach</ul>
                    </div>
                @endif
            </div>
            <aside class="grid" style="gap:20px">
                <div class="card em">
                    <h3>Ringkasan layanan</h3>
                    <dl class="facts">
                        @if ($layanan->kategori)<div><dt>Kategori</dt><dd>{{ $layanan->kategori }}</dd></div>@endif
                        <div><dt>Waktu penyelesaian</dt><dd>{!! Situs::isi($layanan->waktu, 'Waktu') !!}</dd></div>
                        <div><dt>Biaya</dt><dd>{!! Situs::isi($layanan->biaya, 'Biaya') !!}</dd></div>
                        <div><dt>Kontak petugas</dt><dd>{!! Situs::isi($layanan->kontak_petugas, 'Kontak petugas') !!}</dd></div>
                    </dl>
                    @if ($formulir)
                        <a class="btn" style="margin-top:16px" href="{{ $formulir }}" target="_blank" rel="noopener">@include('site.partials.ikon', ['n' => 'unduh', 's' => 18])Unduh formulir</a>
                    @else
                        <p class="src">Formulir belum tersedia untuk diunduh.</p>
                    @endif
                </div>
                <div class="card">
                    <h3>Kantor Camat Long Bagun</h3>
                    <dl class="facts">
                        <div><dt>Jam pelayanan</dt><dd>{!! Situs::isi($site->jam_pelayanan, 'Jam pelayanan') !!}</dd></div>
                        <div><dt>Telepon</dt><dd>{!! Situs::isi($site->phone, 'Nomor telepon') !!}</dd></div>
                    </dl>
                    <a class="more" style="margin-top:14px" href="{{ route('kontak') }}">Lihat alamat dan peta</a>
                </div>
            </aside>
        </div>
    </div></div>
@endsection
