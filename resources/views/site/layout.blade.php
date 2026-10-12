@php
    $namaSitus = $site->name ?: 'Kecamatan Long Bagun';
    // Bila halaman ini dikelola sebagai Halaman Dinamis, judul dan deskripsinya mengikuti isian admin.
    $halaman ??= null;
    $judulHalaman = $halaman?->meta_title ?: ($halaman?->title ?: trim($__env->yieldContent('judul')));
    $judulLengkap = $judulHalaman !== '' ? $judulHalaman . ' · ' . $namaSitus : $namaSitus . ' · Kabupaten Mahakam Ulu';
    $deskripsi = $halaman?->meta_desc ?: trim($__env->yieldContent('deskripsi')) ?: ($site->description ?: 'Situs resmi Kantor Camat Long Bagun, Kabupaten Mahakam Ulu, Kalimantan Timur.');
    $lambang = Situs::berkas($site->logo) ?? asset('images/Mahakam_Ulu.webp');
    $gambarBagi = trim($__env->yieldContent('gambar')) ?: asset('images/hero-mahakam.jpg');
    $wa = Situs::nomorWa();
    $menu = Situs::menu();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $judulLengkap }}</title>
    <meta name="description" content="{{ $deskripsi }}">
    <meta name="theme-color" content="#D1FAE5">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="{{ $judulLengkap }}">
    <meta property="og:description" content="{{ $deskripsi }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $gambarBagi }}">
    <meta property="og:site_name" content="{{ $namaSitus }}">
    <meta property="og:locale" content="id_ID">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" href="{{ Situs::berkas($site->favicon) ?? $lambang }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ filemtime(public_path('css/site.css')) }}">
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'GovernmentOrganization',
        'name' => 'Kantor Camat Long Bagun',
        'url' => route('beranda'),
        'logo' => $lambang,
        'address' => array_filter([
            '@type' => 'PostalAddress',
            'streetAddress' => $site->address,
            'addressLocality' => 'Ujoh Bilang',
            'addressRegion' => 'Kalimantan Timur',
            'postalCode' => '75767',
            'addressCountry' => 'ID',
        ]),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @stack('head')
</head>
<body>
<a class="skip" href="#isi">Lewati ke isi</a>

@if ($site->alert_active && filled($site->alert_text))
    @php $tingkat = in_array($site->alert_level, ['info', 'waspada', 'bahaya']) ? $site->alert_level : 'info'; @endphp
    <div class="alert {{ $tingkat }}" role="alert">
        <div class="in"><b>{{ $tingkat }}</b><span>{{ $site->alert_text }}</span></div>
    </div>
@endif

<div class="shell">
    <header class="head">
        <div class="nav">
            <div class="in">
                <div class="bar">
                    <a class="brand" href="{{ route('beranda') }}">
                        <img class="logo" src="{{ $lambang }}" alt="Lambang Kabupaten Mahakam Ulu" width="38" height="42">
                        <span><b>{{ $namaSitus }}</b><small>Kabupaten Mahakam Ulu</small></span>
                    </a>
                    <nav class="links" aria-label="Menu utama">
                        @foreach ($menu as [$teks, $url, $aktif, $anak])
                            @if ($anak)
                                <div class="dd">
                                    <a href="{{ $url }}" @class(['on' => $aktif]) @if ($aktif) aria-current="page" @endif>{{ $teks }}</a>
                                    <div class="sub"><div>
                                        @foreach ($anak as [$t, $u])
                                            <a href="{{ $u }}">{{ $t }}</a>
                                        @endforeach
                                    </div></div>
                                </div>
                            @else
                                <a href="{{ $url }}" @class(['on' => $aktif]) @if ($aktif) aria-current="page" @endif>{{ $teks }}</a>
                            @endif
                        @endforeach
                    </nav>
                    <a href="{{ route('cari') }}" class="cta-s">@include('site.partials.ikon', ['n' => 'cari', 's' => 16])Cari</a>
                    <details class="mnav">
                        <summary class="burger" aria-label="Buka menu">@include('site.partials.ikon', ['n' => 'menu'])</summary>
                        <div class="panel-m">
                            @foreach ($menu as [$teks, $url, $aktif, $anak])
                                <a href="{{ $url }}">{{ $teks }}</a>
                                @foreach ($anak as [$t, $u])
                                    <a class="child" href="{{ $u }}">{{ $t }}</a>
                                @endforeach
                            @endforeach
                            <a href="{{ route('cari') }}">Cari</a>
                        </div>
                    </details>
                </div>
            </div>
        </div>
    </header>

    <main id="isi">
        @yield('isi')
    </main>
</div>

<footer>
    <div class="in">
        <div class="fg">
            <div>
                <img class="flogo" src="{{ $lambang }}" alt="" loading="lazy">
                <h4>Kantor Camat Long Bagun</h4>
                <p style="margin:0 0 8px">Ujoh Bilang, Kabupaten Mahakam Ulu, Kalimantan Timur 75767</p>
                <p style="margin:0">Telepon : {!! Situs::isi($site->phone, 'Nomor telepon') !!} <br>Email : {!! Situs::isi($site->email, 'Email resmi') !!}<br>Jam pelayanan : {!! Situs::isi($site->jam_pelayanan, 'Jam pelayanan') !!}</p>
                <p style="margin:10px 0 0"><a href="{{ route('kontak') }}" style="font-weight:600">Lihat peta lokasi</a></p>
            </div>
            <div>
                <h4>Tautan instansi</h4>
                <ul>
                    @foreach ($site->footer_links_related ?? [] as $tautan)
                        @if (filled($tautan['url'] ?? null))
                            <li><a href="{{ $tautan['url'] }}" rel="noopener" target="_blank">{{ $tautan['label'] ?? $tautan['url'] }}</a></li>
                        @endif
                    @endforeach
                    <li><a href="{{ route('ppid.index') }}">PPID Kecamatan</a></li>
                </ul>
            </div>
            <div>
                <h4>Jelajahi</h4>
                <ul>
                    <li><a href="{{ url('/profil/sejarah') }}">Profil kecamatan</a></li>
                    <li><a href="{{ route('kampung.index') }}">Sebelas kampung</a></li>
                    <li><a href="{{ route('layanan.index') }}">Layanan</a></li>
                    <li><a href="{{ route('data.index') }}">Data wilayah</a></li>
                    <li><a href="{{ route('kontak') }}">Kontak</a></li>
                </ul>
            </div>
        </div>
        <div class="fb">{{ $namaSitus }} · Kode wilayah 64.11.01 · {{ now()->year }}</div>
    </div>
</footer>

<details class="wabox">
    <summary class="wa" aria-label="Hubungi kantor camat lewat WhatsApp">@include('site.partials.ikon', ['n' => 'wa', 's' => 26])</summary>
    <div class="wapop">
        <div class="wahead">@include('site.partials.ikon', ['n' => 'wa', 's' => 20])<div><b>Kantor Camat Long Bagun</b><span>WhatsApp layanan</span></div></div>
        <p>Ada yang bisa kami bantu? Kirim pertanyaan seputar layanan kantor camat lewat WhatsApp.</p>
        @if ($wa)
            <a class="btn wago" href="https://wa.me/{{ $wa }}?text={{ urlencode('Halo Kantor Camat Long Bagun, saya ingin bertanya.') }}" rel="noopener" target="_blank">Mulai chat</a>
        @else
            <p class="small muted">Nomor WhatsApp kantor belum diisi. Sementara itu, pesan bisa dikirim lewat formulir pengaduan.</p>
            <a class="btn alt" href="{{ route('layanan.pengaduan') }}">Kirim pesan</a>
        @endif
    </div>
</details>

@stack('skrip')
</body>
</html>
