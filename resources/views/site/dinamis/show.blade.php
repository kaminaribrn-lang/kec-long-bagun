{{--
    Halaman Dinamis (menu admin "Dynamic Pages") untuk templat isi.
    Templat modul tidak lewat sini; lihat SiteController::halaman().
    $halaman: DynamicPage, $isi: array content, $induk: menu induk (atau null), $pegawai: koleksi Aparatur.
--}}
@extends('site.layout')

@php
    $t = $halaman->template;
    // Unggahan Filament bisa berupa string atau array berisi satu path.
    $berkas = fn ($v) => Situs::berkas(is_array($v) ? (reset($v) ?: null) : $v);
    // Isi editor: HTML (RichEditor) atau susunan TipTap bila editor menyimpan JSON.
    $html = function ($v) {
        if (is_array($v)) {
            return class_exists(\Filament\Forms\Components\RichEditor\RichContentRenderer::class)
                ? \Filament\Forms\Components\RichEditor\RichContentRenderer::make($v)->toHtml()
                : '';
        }

        return (string) $v;
    };
    $kosong = fn ($v) => blank(is_string($v) ? trim(strip_tags($v)) : $v);
    $jejak = array_values(array_filter([$induk ? [$induk->name, null] : null, [$halaman->title, null]]));
@endphp

@section('isi')
    @include('site.partials.phero', ['judul' => $halaman->title, 'crumbs' => $jejak])

    <div class="page"><div class="in">
        @switch($t)
            {{-- Penjelasan 1: satu judul dan satu isi --}}
            @case('penjelasan_1')
                <div class="prose">
                    @if (filled($isi['judul'] ?? null))<h2 style="margin-top:0">{{ $isi['judul'] }}</h2>@endif
                    @if ($kosong($isi['isi_konten'] ?? null))
                        <p class="tbd">[ISI HALAMAN BELUM DIISI]</p>
                    @else
                        {!! $html($isi['isi_konten']) !!}
                    @endif
                </div>
                @break

            {{-- Penjelasan 2: dua panel berdampingan --}}
            @case('penjelasan_2')
                <div class="grid g2">
                    @foreach ([1, 2] as $n)
                        <div @class(['card', 'em' => $n === 1])>
                            <h2>{{ $isi["box_{$n}_title"] ?? '' }}</h2>
                            <div class="prose">
                                @if ($kosong($isi["box_{$n}_content"] ?? null))
                                    <p class="tbd">[ISI BELUM DIISI]</p>
                                @else
                                    {!! $html($isi["box_{$n}_content"]) !!}
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                @break

            {{-- Aparatur: pimpinan dan daftar pegawai. Data utama dari menu admin Pegawai. --}}
            @case('aparatur')
                @php
                    $utama = filled(data_get($isi, 'main_leader.name'))
                        ? (object) ['name' => data_get($isi, 'main_leader.name'), 'position' => data_get($isi, 'main_leader.position'), 'photo' => data_get($isi, 'main_leader.photo')]
                        : $pegawai->first();
                    $lain = $pegawai->isNotEmpty()
                        ? $pegawai->reject(fn ($p) => $utama && isset($utama->id) && $p->id === $utama->id)->values()
                        : collect(data_get($isi, 'aparaturs', []))->filter(fn ($a) => is_array($a) && filled($a['name'] ?? null))->map(fn ($a) => (object) ['name' => $a['name'], 'position' => $a['position'] ?? null, 'photo' => $a['photo'] ?? null, 'atasan' => null]);
                @endphp
                @if ($utama)
                    <div class="org"><div class="p lead-p" style="grid-column:1/-1">
                        @include('site.partials.foto', ['path' => is_array($utama->photo) ? reset($utama->photo) : $utama->photo, 'label' => $utama->position ?: 'Pimpinan', 'alt' => 'Foto ' . $utama->name, 'bentuk' => 'tall'])
                        <b>{{ $utama->name }}</b><span>{{ $utama->position }}</span>
                    </div></div>
                @endif
                @if ($lain->isNotEmpty())
                    <div class="org">
                        @foreach ($lain as $p)
                            <div class="p">
                                @include('site.partials.foto', ['path' => is_array($p->photo) ? reset($p->photo) : $p->photo, 'label' => $p->position ?: 'Pegawai', 'alt' => 'Foto ' . $p->name, 'bentuk' => 'tall'])
                                <b>{{ $p->name }}</b><span>{{ $p->position }}</span>
                                @if ($p->atasan ?? null)<span class="small">Atasan: {{ $p->atasan->position }}</span>@endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="card" style="max-width:640px;margin:0 auto;text-align:center">
                        <p class="muted" style="margin:0">Data sekretaris camat dan para kepala seksi belum diisi: {!! Situs::isi(null, 'Nama Sekcam') !!}, {!! Situs::isi(null, 'Nama Kasi') !!}.</p>
                    </div>
                @endif
                @break

            {{-- Profil pimpinan dengan riwayat jabatan --}}
            @case('profil_pimpinan')
                <div class="grid" style="gap:24px">
                    @forelse (collect($isi['leaders'] ?? [])->filter(fn ($l) => is_array($l) && filled($l['name'] ?? null)) as $l)
                        <div class="card">
                            <div class="split" style="grid-template-columns:minmax(0,220px) minmax(0,1fr)">
                                <div>@include('site.partials.foto', ['path' => is_array($l['photo'] ?? null) ? reset($l['photo']) : ($l['photo'] ?? null), 'label' => $l['position'] ?? 'Pimpinan', 'alt' => 'Foto ' . $l['name'], 'bentuk' => 'tall'])</div>
                                <div>
                                    @if (filled($l['label'] ?? null))<span class="badge g">{{ $l['label'] }}</span>@endif
                                    <h2 style="margin-top:8px">{{ $l['name'] }}</h2>
                                    <p class="muted" style="margin:0 0 12px">{{ $l['position'] ?? '' }}</p>
                                    <dl class="facts">
                                        @foreach (['nip' => 'NIP', 'pangkat' => 'Pangkat', 'golongan' => 'Golongan', 'pendidikan' => 'Pendidikan'] as $k => $label)
                                            @if (filled($l[$k] ?? null))<div><dt>{{ $label }}</dt><dd>{{ $l[$k] }}</dd></div>@endif
                                        @endforeach
                                    </dl>
                                    @if (filled($l['quote'] ?? null))<blockquote class="prose" style="margin:16px 0 0;padding-left:14px;border-left:3px solid var(--gold)">{{ $l['quote'] }}</blockquote>@endif
                                    @if (filled($l['histories'] ?? null))
                                        <h3 style="margin-top:20px">Riwayat jabatan</h3>
                                        <div class="rows">
                                            @foreach ($l['histories'] as $r)
                                                @continue(! is_array($r))
                                                <div class="rowi" style="padding:10px 14px"><div class="when" style="width:auto;padding:6px 10px"><span>{{ $r['year_start'] ?? '' }}{{ ($r['is_current'] ?? false) ? ' – kini' : (filled($r['year_end'] ?? null) ? ' – ' . $r['year_end'] : '') }}</span></div><div class="grow"><h3>{{ $r['position'] ?? '' }}</h3><span class="small muted">{{ $r['institution'] ?? '' }}</span></div></div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card" style="text-align:center"><p class="tbd" style="margin:0">[PROFIL PIMPINAN BELUM DIISI]</p></div>
                    @endforelse
                </div>
                @break

            {{-- Gambar tunggal --}}
            @case('gambar_1')
                <figure style="margin:0">
                    <div class="img" style="min-height:320px;margin:0">@include('site.partials.foto', ['path' => is_array($isi['image'] ?? null) ? reset($isi['image']) : ($isi['image'] ?? null), 'label' => $halaman->title, 'alt' => $isi['caption'] ?? $halaman->title])</div>
                    @if (filled($isi['caption'] ?? null))<figcaption class="kredit">{{ $isi['caption'] }}</figcaption>@endif
                </figure>
                @break

            {{-- Galeri foto --}}
            @case('galeri')
                <div class="galeri">
                    @forelse (collect($isi['images'] ?? [])->filter(fn ($g) => is_array($g) && $berkas($g['image'] ?? null)) as $g)
                        <figure><img src="{{ $berkas($g['image']) }}" alt="{{ $g['caption'] ?? $halaman->title }}" loading="lazy" decoding="async">@if (filled($g['caption'] ?? null))<figcaption>{{ $g['caption'] }}</figcaption>@endif</figure>
                    @empty
                        <div class="card" style="grid-column:1/-1;text-align:center"><p class="muted" style="margin:0">Belum ada foto di halaman ini.</p></div>
                    @endforelse
                </div>
                @break

            {{-- Dokumen: daftar manual, atau otomatis dari kategori PPID --}}
            @case('dokumen_grid')
            @case('dokumen_list')
            @case('tabel')
                @php
                    $dokumen = collect();
                    if (($isi['mode'] ?? 'manual') === 'automatic' && filled($isi['ppid_id'] ?? null)) {
                        $kategori = \App\Models\Ppid::with(['items', 'children.items'])->find($isi['ppid_id']);
                        $dokumen = collect($kategori?->items ?? [])->concat(collect($kategori?->children ?? [])->flatMap->items)
                            ->map(fn ($d) => ['name' => $d->name, 'date' => $d->tanggal_pembuatan, 'description' => $d->description, 'file' => $d->file]);
                    } else {
                        $dokumen = collect($isi['documents'] ?? [])->filter(fn ($d) => is_array($d) && filled($d['name'] ?? null))
                            ->map(fn ($d) => ['name' => $d['name'], 'date' => $d['date'] ?? null, 'description' => $d['description'] ?? null, 'file' => $d['file_url'] ?? null]);
                    }
                @endphp
                @if ($dokumen->isEmpty())
                    <div class="card" style="text-align:center"><p class="muted" style="margin:0">Belum ada dokumen di halaman ini.</p></div>
                @elseif ($t === 'dokumen_list')
                    <div class="rows">
                        @foreach ($dokumen as $d)
                            <div class="rowi">
                                <div class="grow"><h3>{{ $d['name'] }}</h3><span class="small muted">{{ $d['date'] }}@if (filled($d['description'])) · {{ $d['description'] }}@endif</span></div>
                                @if ($u = $berkas($d['file']))<a class="btn sm" href="{{ $u }}" target="_blank" rel="noopener">@include('site.partials.ikon', ['n' => 'unduh', 's' => 16])Unduh</a>@else<span class="small muted">Berkas belum diunggah</span>@endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="tblw">
                        <table class="t">
                            <thead><tr><th>Dokumen</th><th>Tanggal / tahun</th><th>Keterangan</th><th>Berkas</th></tr></thead>
                            <tbody>
                                @foreach ($dokumen as $d)
                                    <tr><td><b>{{ $d['name'] }}</b></td><td>{{ $d['date'] }}</td><td>{{ $d['description'] }}</td><td>@if ($u = $berkas($d['file']))<a href="{{ $u }}" target="_blank" rel="noopener" style="color:var(--g);font-weight:600">Unduh</a>@else<span class="muted">Belum ada</span>@endif</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                @break

            {{-- Editor bebas: teks Markdown dan media pendukung --}}
            @default
                <div class="prose">
                    @if ($kosong($isi['isi_konten'] ?? null))
                        <p class="tbd">[ISI HALAMAN BELUM DIISI]</p>
                    @elseif (is_string($isi['isi_konten']) && $isi['isi_konten'] === strip_tags($isi['isi_konten']))
                        {!! Str::markdown($isi['isi_konten'], ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                    @else
                        {!! $html($isi['isi_konten']) !!}
                    @endif
                </div>
                @if (filled($isi['media_items'] ?? null))
                    <div class="galeri" style="margin-top:28px">
                        @foreach ($isi['media_items'] as $m)
                            @continue(! is_array($m))
                            @php $gambar = $berkas($m['upload'] ?? null); $video = $berkas($m['video_upload'] ?? null) ?: ($m['url'] ?? null); @endphp
                            @if (($m['type'] ?? 'image') === 'video' && $video)
                                <figure><video src="{{ $video }}" controls preload="none" style="width:100%;display:block"></video>@if (filled($m['caption'] ?? null))<figcaption>{{ $m['caption'] }}</figcaption>@endif</figure>
                            @elseif ($gambar)
                                <figure><img src="{{ $gambar }}" alt="{{ $m['caption'] ?? $halaman->title }}" loading="lazy" decoding="async">@if (filled($m['caption'] ?? null))<figcaption>{{ $m['caption'] }}</figcaption>@endif</figure>
                            @endif
                        @endforeach
                    </div>
                @endif
        @endswitch
    </div></div>
@endsection
