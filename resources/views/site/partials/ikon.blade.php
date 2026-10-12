@php
    // Satu set ikon garis 1,75 px. Kunci "ikon" pada koleksi Layanan memilih salah satunya.
    $ikon = [
        'ktp' => '<rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="9" cy="11" r="2.2"/><path d="M5.8 16c.6-1.6 1.8-2.4 3.2-2.4s2.6.8 3.2 2.4M14.5 10h4M14.5 13h3"/>',
        'pindah' => '<path d="M3 11l9-7 9 7"/><path d="M5 10v9h6v-5"/><path d="M15 17h6M18 14l3 3-3 3"/>',
        'stempel' => '<path d="M9 13V9a3 3 0 1 1 6 0v4"/><path d="M5 13h14l1 4H4z"/><path d="M6 20h12"/>',
        'usaha' => '<path d="M4 9l1.5-5h13L20 9"/><path d="M4 9h16v2a2.7 2.7 0 0 1-5.3 0 2.7 2.7 0 0 1-5.4 0A2.7 2.7 0 0 1 4 11z"/><path d="M5.5 13.5V20h13v-6.5M10 20v-4h4v4"/>',
        'dokumen' => '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5M9 13h6M9 17h4"/>',
        'unduh' => '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5M12 11v6M9 14l3 3 3-3"/>',
        'pesan' => '<path d="M20 15a2 2 0 0 1-2 2H8l-4 3.5V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2z"/><path d="M8.5 9h7M8.5 12.5h4.5"/>',
        'cari' => '<circle cx="11" cy="11" r="6.5"/><path d="M16 16l4 4"/>',
        'menu' => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'panah' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'toa' => '<path d="M4 10v4a1 1 0 0 0 1 1h2l5 4V5L7 9H5a1 1 0 0 0-1 1z"/><path d="M16 9a4 4 0 0 1 0 6M18.5 6.5a7.5 7.5 0 0 1 0 11"/>',
        'kalender' => '<rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/>',
        'jam' => '<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/>',
        'telepon' => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/>',
        'surat' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3.5 7l8.5 6 8.5-6"/>',
        'pin' => '<path d="M12 21s7-6.2 7-11.5A7 7 0 0 0 5 9.5C5 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/>',
        'wa' => '<path d="M4 20l1.3-4A8 8 0 1 1 8 18.7z"/><path d="M9 9.5c0 3 2.5 5.5 5.5 5.5l1-1.5-2-1-1 .8a4 4 0 0 1-2-2l.8-1-1-2z"/>',
    ];
@endphp
<svg class="ic" viewBox="0 0 24 24" aria-hidden="true" @isset($s) style="width:{{ $s }}px;height:{{ $s }}px" @endisset>{!! $ikon[$n ?? 'dokumen'] ?? $ikon['dokumen'] !!}</svg>
