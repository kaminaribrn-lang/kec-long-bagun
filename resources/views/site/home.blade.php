@extends('site.layout')

@php
    $heroFoto = Situs::berkas($site->hero_image);
    $utama = $layanans->first();
    $beritaUtama = $berita->first();
@endphp

@section('isi')
    {{-- 1. Gambar utama dengan dua tombol dan angka kunci --}}
    <section class="hero">
        <div class="hphoto" @if ($heroFoto) style="--himg:url('{{ $heroFoto }}')" @endif></div>
        <div class="htint"></div>
        <div class="dots"></div>
        <div class="in">
            <div class="hgrid">
                <div>
                    <span class="pill anim"><i></i>Portal resmi · Hulu Sungai Mahakam</span>
                    <h1 class="anim d2">Kecamatan<em>Long Bagun</em></h1>
                    <div class="gl"></div>
                    <p class="sub anim d3">{{ $site->welcome_subtitle ?: 'Syarat layanan, data sebelas kampung, berita, dan dokumen publik Kantor Camat Long Bagun dalam satu tempat.' }}</p>
                    <div class="btns anim d3">
                        <a href="{{ route('layanan.index') }}" class="b1">Lihat layanan @include('site.partials.ikon', ['n' => 'panah', 's' => 18])</a>
                    </div>
                </div>
                <div class="card-img anim d2">
                    <div class="glow"></div>
                    <div class="fr">
                        @php $fotoCamat = Situs::berkas($camat?->photo); @endphp
                        <div class="ph" @if ($fotoCamat) style="background:linear-gradient(180deg,transparent 55%,rgba(255,255,255,.5)),url('{{ $fotoCamat }}') center/cover" @endif>
                            @if ($fotoCamat)<span></span>@else<span class="phl">[FOTO: Camat Long Bagun]</span>@endif
                            <div class="nm"><b>{!! Situs::isi($camat?->name, 'Nama camat') !!}</b><span>{{ $camat?->position ?: 'Camat Long Bagun' }}</span></div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($kunci->isNotEmpty())
                <div class="stat">
                    @foreach ($kunci as $s)
                        <div>
                            <b>{{ Situs::angka($s->nilai) }}</b>
                            <span>{{ $s->label }}@if ($s->satuan && ! in_array($s->satuan, ['kampung', 'RT'])) ({{ $s->satuan }})@endif</span>
                            <!-- <small>{{ $s->sumber }}@if ($s->tahun), {{ $s->tahun }}@endif</small> -->
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="hcred">data : BPS Mahakam Ulu
 </div>
        </div>
    </section>

    {{-- 2. Layanan cepat --}}
    <section class="sx alt">
        <div class="in">
            <div class="sh"><span class="tag"><i></i>Layanan</span><h2>Urus dokumen <span>tanpa bolak-balik</span></h2><p>Cek syarat dan alur sebelum datang ke kantor camat.</p></div>
            <div class="bento">
                @if ($utama)
                    <a href="{{ route('layanan.show', $utama->slug) }}" class="bx big">
                        <span class="orn orn-card" aria-hidden="true"></span>
                        <span class="chip">@include('site.partials.ikon', ['n' => $utama->ikon])</span>
                        <h3>{{ $utama->nama }}</h3>
                        @if ($utama->ringkasan)<p>{{ $utama->ringkasan }}</p>@endif
                        <ul>
                            @forelse (collect($utama->syarat)->take(3) as $syarat)
                                <li>{{ is_array($syarat) ? ($syarat['teks'] ?? '') : $syarat }}</li>
                            @empty
                                <li>{!! Situs::isi(null, 'Syarat') !!}</li>
                            @endforelse
                        </ul>
                        <span class="go">Lihat syarat lengkap @include('site.partials.ikon', ['n' => 'panah', 's' => 16])</span>
                    </a>
                @endif
                @foreach ($layanans->skip(1) as $l)
                    <a href="{{ route('layanan.show', $l->slug) }}" class="bx">
                        <span class="chip">@include('site.partials.ikon', ['n' => $l->ikon])</span>
                        <h3>{{ $l->nama }}</h3>
                        <span class="go">Syarat dan alur</span>
                    </a>
                @endforeach
                <a href="{{ route('layanan.formulir') }}" class="bx">
                    <span class="chip">@include('site.partials.ikon', ['n' => 'unduh'])</span>
                    <h3>Unduh formulir</h3>
                    <span class="go">Semua formulir</span>
                </a>
                <a href="{{ route('layanan.pengaduan') }}" class="bx wide">
                    <span class="chip">@include('site.partials.ikon', ['n' => 'pesan'])</span>
                    <div><h3>Pengaduan warga</h3><p>Sampaikan keluhan atau masukan. Pesan langsung masuk ke kantor camat.</p></div>
                </a>
            </div>
        </div>
    </section>

    {{-- 3. Berita dan pengumuman --}}
    <section class="sx">
        <div class="in">
            <div class="sh row"><div><span class="tag"><i></i>Informasi</span><h2>Berita dan <span>pengumuman</span></h2></div><a class="more" style="margin:0" href="{{ route('berita.index') }}">Semua berita @include('site.partials.ikon', ['n' => 'panah', 's' => 16])</a></div>
            <div class="news">
                @if ($beritaUtama)
                    <div class="ncol">
                    <a href="{{ route('berita.show', $beritaUtama->slug) }}" class="feat">
                        <div class="img">@include('site.partials.foto', ['path' => $beritaUtama->file?->storage_path ?? $beritaUtama->file?->path, 'label' => Str::limit($beritaUtama->title, 40), 'alt' => $beritaUtama->title])</div>
                        <div class="body">
                            <div class="meta">@if ($beritaUtama->category)<span class="cat">{{ $beritaUtama->category->name }}</span>@endif<span>{{ optional($beritaUtama->published_at)->translatedFormat('j F Y') }}</span></div>
                            <h3>{{ $beritaUtama->title }}</h3>
                            <p>{{ $beritaUtama->ringkasan ?: Str::limit(strip_tags((string) $beritaUtama->content), 180) }}</p>
                        </div>
                    </a>
                    @if ($berita->count() > 1)
                        <div class="nlist">
                            @foreach ($berita->skip(1) as $b)
                                <a href="{{ route('berita.show', $b->slug) }}">
                                    <div class="img">@include('site.partials.foto', ['path' => $b->file?->storage_path ?? $b->file?->path, 'label' => 'Foto', 'alt' => $b->title])</div>
                                    <div>
                                        <div class="meta">@if ($b->category)<span class="cat">{{ $b->category->name }}</span>@endif<span>{{ optional($b->published_at)->translatedFormat('j F Y') }}</span></div>
                                        <h3>{{ $b->title }}</h3>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                    </div>
                @else
                    <div class="side"><div class="empty">Belum ada berita. Berita yang diterbitkan admin tampil di sini.</div></div>
                @endif
                <aside class="side">
                    <h3>@include('site.partials.ikon', ['n' => 'toa'])Pengumuman</h3>
                    @forelse ($pengumuman as $p)
                        <a class="rowi" style="padding:12px 14px" href="{{ route('pengumuman.show', $p->slug) }}"><div class="grow" style="min-width:0"><h3>{{ $p->title }}</h3><span class="small muted">{{ optional($p->published_at)->translatedFormat('j F Y') }}</span></div></a>
                    @empty
                        <div class="empty"><span class="chip">@include('site.partials.ikon', ['n' => 'toa'])</span>Belum ada pengumuman.</div>
                    @endforelse
                    <h3 style="margin-top:6px">@include('site.partials.ikon', ['n' => 'kalender'])Agenda</h3>
                    @forelse ($agenda as $a)
                        <a class="rowi" style="padding:10px 14px" href="{{ route('agenda') }}">
                            <div class="when"><b>{{ $a->schedule->format('j') }}</b><span>{{ $a->schedule->translatedFormat('M') }}</span></div>
                            <div class="grow" style="min-width:0"><h3>{{ $a->caption }}</h3><span class="small muted">{{ $a->location }}</span></div>
                        </a>
                    @empty
                        <div class="empty">Belum ada agenda kegiatan.</div>
                    @endforelse
                </aside>
            </div>
        </div>
    </section>

    {{-- 4. Sebelas kampung dengan peta --}}
    @if ($kampungs->isNotEmpty())
        <section class="sx em" id="kampung">
            <div class="orn orn-med" aria-hidden="true"></div>
            <div class="orn orn-med2" aria-hidden="true"></div>
            <div class="in">
                <div class="sh"><span class="tag"><i></i>Wilayah</span><h2>{{ $kampungs->count() === 11 ? 'Sebelas' : $kampungs->count() }} kampung di <span>hulu Mahakam</span></h2><p>Pilih kampung untuk melihat petinggi, luas, penduduk, dan potensinya.</p></div>
                <div class="kgrid">
                    <div class="kleft">
                        <div class="pills">
                            @foreach ($kampungs as $i => $k)
                                <a href="{{ route('kampung.show', $k->slug) }}" @class(['kp', 'on' => $i === 0]) data-kampung="{{ $k->slug }}"><i style="background:var(--g2)"></i>{{ $k->nama }}</a>
                            @endforeach
                        </div>
                        @foreach ($kampungs as $i => $k)
                            <div class="panel kcard" data-info="{{ $k->slug }}" @if ($i > 0) hidden @endif>
                                <div class="kinfo">
                                    <div class="wide"><span>Kampung · kode wilayah {{ $k->kode_wilayah }}</span><b>{{ $k->nama }}</b></div>
                                    <div><span>Penduduk {{ $k->tahun_data }}</span><b>{{ Situs::angka($k->penduduk) }} jiwa</b></div>
                                    <div><span>Luas</span><b>{{ Situs::angka($k->luas_km2, 2) }} km²</b></div>
                                    <div><span>Jumlah RT</span><b>{{ $k->jumlah_rt }}</b></div>
                                    <div><span>Petinggi</span><b style="font-size:13px;font-weight:600">{!! Situs::isi($k->nama_petinggi, 'Nama petinggi') !!}</b></div>
                                </div>
                                <p class="knote">Sumber angka: {{ $k->sumber_data }}. <a href="{{ route('kampung.show', $k->slug) }}" style="color:var(--g);font-weight:600">Lihat profil {{ $k->nama }}</a></p>
                            </div>
                        @endforeach
                    </div>
                    <div class="panel">
                        <h3>Peta Kecamatan Long Bagun</h3>
                        <p class="src" style="margin:0 0 12px">Batas kampung dari BIG (delineasi batas desa 2018, bersifat indikatif).</p>
                        @include('site.partials.peta', ['kampungs' => $kampungs, 'terpilih' => $kampungs->first()->slug])
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- 5. Tiga destinasi --}}
    @if ($destinasis->isNotEmpty())
        <section class="sx">
            <div class="in">
                <div class="sh row"><div><span class="tag"><i></i>Potensi dan wisata</span><h2>Jelajahi <span>Long Bagun</span></h2></div><a class="more" style="margin:0" href="{{ route('wisata.index') }}">Semua destinasi @include('site.partials.ikon', ['n' => 'panah', 's' => 16])</a></div>
                <div class="wis">
                    @foreach ($destinasis as $d)
                        <a href="{{ route('wisata.show', $d->slug) }}" class="wc">
                            <div class="img">@include('site.partials.foto', ['path' => $d->foto_utama, 'label' => $d->nama, 'alt' => $d->foto_alt ?: $d->nama])</div>
                            <div class="body"><span class="jn">{{ $d->jenis_label }}@if ($d->lokasi) · {{ $d->lokasi }}@endif</span><h3>{{ $d->nama }}</h3><p>{{ Str::limit($d->uraian, 120) }}</p></div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

@push('skrip')
<script>
// Pilih kampung dari tombol atau dari peta tanpa pindah halaman.
(function () {
    var sec = document.getElementById('kampung');
    if (!sec) return;
    function pilih(slug) {
        sec.querySelectorAll('[data-kampung]').forEach(function (el) {
            var on = el.getAttribute('data-kampung') === slug;
            (el.querySelector('.ds') || el).classList.toggle('on', on);
        });
        sec.querySelectorAll('[data-info]').forEach(function (el) { el.hidden = el.getAttribute('data-info') !== slug; });
    }
    sec.addEventListener('click', function (e) {
        var el = e.target.closest('[data-kampung]');
        if (!el) return;
        e.preventDefault();
        pilih(el.getAttribute('data-kampung'));
    });
})();
</script>
@endpush
