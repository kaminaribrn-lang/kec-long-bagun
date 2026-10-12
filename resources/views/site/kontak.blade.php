@extends('site.layout')
@section('judul', 'Kontak')
@section('deskripsi', 'Alamat, jam pelayanan, dan kontak Kantor Camat Long Bagun.')

@section('isi')
    @include('site.partials.phero', ['judul' => 'Kontak dan lokasi', 'lead' => 'Alamat, jam pelayanan, dan cara menghubungi Kantor Camat Long Bagun.', 'crumbs' => [['Kontak', null]]])
    <div class="page"><div class="in">
        <div class="split">
            <div class="card">
                <h2>Lokasi kantor</h2>
                <div class="mapbox">
                    @if (filled($site->google_maps_embed) && Str::contains($site->google_maps_embed, '<iframe'))
                        {!! $site->google_maps_embed !!}
                    @elseif ($site->latitude && $site->longitude)
                        <iframe title="Peta lokasi Kantor Camat Long Bagun" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.openstreetmap.org/export/embed.html?bbox={{ $site->longitude - 0.02 }}%2C{{ $site->latitude - 0.012 }}%2C{{ $site->longitude + 0.02 }}%2C{{ $site->latitude + 0.012 }}&amp;layer=mapnik&amp;marker={{ $site->latitude }}%2C{{ $site->longitude }}"></iframe>
                    @else
                        <div class="ph-box"><span class="phl">[PETA LOKASI]</span></div>
                    @endif
                </div>
                @if ($site->latitude && $site->longitude)
                    <p class="src">Koordinat {{ Situs::angka($site->latitude, 4) }}; {{ Situs::angka($site->longitude, 4) }}. <a href="https://www.google.com/maps?q={{ $site->latitude }},{{ $site->longitude }}" target="_blank" rel="noopener">Buka di Google Maps</a></p>
                @endif
            </div>
            <aside class="grid" style="gap:20px">
                <div class="card em">
                    <h3>Kantor Camat Long Bagun</h3>
                    <dl class="facts">
                        <div><dt>Alamat</dt><dd>{!! Situs::isi($site->address, 'Alamat kantor') !!}</dd></div>
                        <div><dt>Jam pelayanan</dt><dd>{!! Situs::isi($site->jam_pelayanan, 'Jam pelayanan') !!}</dd></div>
                        <div><dt>Telepon</dt><dd>{!! Situs::isi($site->phone, 'Nomor telepon') !!}</dd></div>
                        <div><dt>WhatsApp</dt><dd>{!! Situs::isi($site->whatsapp, 'Nomor WhatsApp') !!}</dd></div>
                        <div><dt>Email</dt><dd>{!! Situs::isi($site->email, 'Email resmi') !!}</dd></div>
                        <div><dt>Kode pos</dt><dd>75767</dd></div>
                    </dl>
                    @if ($wa = Situs::nomorWa())
                        <a class="btn" style="margin-top:16px" href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener">@include('site.partials.ikon', ['n' => 'wa', 's' => 18])Kirim WhatsApp</a>
                    @endif
                </div>
                <a class="card" href="{{ route('layanan.pengaduan') }}"><h3 style="margin:0">Pengaduan warga</h3><p class="muted small" style="margin:0">Sampaikan keluhan atau masukan lewat formulir.</p></a>
                @if (filled($site->social_links))
                    <div class="card">
                        <h3>Media sosial</h3>
                        <div class="rows">
                            @foreach ($site->social_links as $s)
                                @if (filled($s['url'] ?? null))<a class="rowi" style="padding:10px 14px" href="{{ $s['url'] }}" target="_blank" rel="noopener">{{ Str::headline($s['platform'] ?? 'Tautan') }}</a>@endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div></div>
@endsection
