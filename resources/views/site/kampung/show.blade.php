@extends('site.layout')
@section('judul', 'Kampung ' . $kampung->nama)
@section('deskripsi', Str::limit($kampung->uraian ?: 'Profil Kampung ' . $kampung->nama . ', Kecamatan Long Bagun.', 155))

@section('isi')
    @include('site.partials.phero', ['judul' => 'Kampung ' . $kampung->nama, 'lead' => $kampung->uraian, 'crumbs' => [['Kampung', route('kampung.index')], [$kampung->nama, null]]])
    <div class="page"><div class="in">
        <div class="split">
            <div class="grid" style="gap:24px">
                <div>
                    <div class="img wide" style="margin:0">@if (Situs::berkas($kampung->foto_utama))@include('site.partials.foto', ['path' => $kampung->foto_utama, 'label' => 'Kampung ' . $kampung->nama, 'alt' => $kampung->foto_alt ?: 'Kampung ' . $kampung->nama])@else @include('site.partials.peta-mini', ['kode' => $kampung->kode_wilayah, 'nama' => $kampung->nama])@endif</div>
                    @if ($kampung->foto_utama && $kampung->foto_kredit)<p class="kredit">Foto: {{ $kampung->foto_kredit }}</p>@endif
                </div>

                <div class="card"><h2>Tentang kampung</h2><div class="prose">
                    @if (filled($kampung->uraian))<p>{{ $kampung->uraian }}</p>@else<p>{!! Situs::isi(null, 'Uraian kampung') !!}</p>@endif
                </div></div>

                <div class="card"><h2>Potensi</h2><div class="prose">
                    @if (filled($kampung->potensi)){!! nl2br(e($kampung->potensi)) !!}@else<p>{!! Situs::isi(null, 'Potensi kampung') !!}</p>@endif
                </div></div>

                @if ($destinasis->isNotEmpty())
                    <div class="card"><h2>Wisata dan budaya di kampung ini</h2>
                        <div class="rows">
                            @foreach ($destinasis as $d)
                                <a class="rowi" href="{{ route('wisata.show', $d->slug) }}"><div class="grow"><h3>{{ $d->nama }}</h3><span class="small muted">{{ $d->jenis_label }}</span></div>@include('site.partials.ikon', ['n' => 'panah', 's' => 18])</a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (filled($kampung->galeri))
                    <div class="card"><h2>Galeri</h2>
                        <div class="galeri">
                            @foreach ($kampung->galeri as $foto)
                                @php $src = Situs::berkas(is_array($foto) ? ($foto['foto'] ?? null) : $foto); @endphp
                                @if ($src)
                                    <figure>
                                        <img src="{{ $src }}" alt="{{ is_array($foto) ? ($foto['alt'] ?? 'Foto Kampung ' . $kampung->nama) : 'Foto Kampung ' . $kampung->nama }}" loading="lazy">
                                        @if (is_array($foto) && filled($foto['kredit'] ?? null))<figcaption>Foto: {{ $foto['kredit'] }}</figcaption>@endif
                                    </figure>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <aside class="grid" style="gap:20px">
                <div class="card em">
                    <h3>Fakta kampung</h3>
                    <dl class="facts">
                        <div><dt>Petinggi</dt><dd>{!! Situs::isi($kampung->nama_petinggi, 'Nama petinggi ' . $kampung->nama) !!}</dd></div>
                        <div><dt>Luas</dt><dd>{{ Situs::angka($kampung->luas_km2, 2) }} km²</dd></div>
                        <div><dt>Penduduk {{ $kampung->tahun_data }}</dt><dd>{{ Situs::angka($kampung->penduduk) }} jiwa</dd></div>
                        <div><dt>Kepadatan</dt><dd>{{ Situs::angka($kampung->kepadatan, 1) }} jiwa/km²</dd></div>
                        <div><dt>Jumlah RT</dt><dd>{{ $kampung->jumlah_rt }}</dd></div>
                        <div><dt>Kode wilayah</dt><dd>{{ $kampung->kode_wilayah }}</dd></div>
                    </dl>
                    <p class="src">Sumber: {{ $kampung->sumber_data }}, {{ $kampung->tahun_data }}.</p>
                </div>
                <div class="card">
                    <h3>Petinggi</h3>
                    @include('site.partials.foto', ['path' => $kampung->foto_petinggi, 'label' => 'Petinggi ' . $kampung->nama, 'alt' => 'Foto petinggi Kampung ' . $kampung->nama, 'bentuk' => 'tall'])
                </div>
                <div class="card">
                    <h3>Kontak kantor kampung</h3>
                    <dl class="facts">
                        <div><dt>Alamat</dt><dd>{!! Situs::isi($kampung->alamat_kantor, 'Alamat kantor kampung') !!}</dd></div>
                        <div><dt>Kontak</dt><dd>{!! Situs::isi($kampung->kontak, 'Kontak kampung') !!}</dd></div>
                    </dl>
                </div>
                <div class="card">
                    <h3>Letak di kecamatan</h3>
                    <div style="display:flex;flex-direction:column;min-height:340px">@include('site.partials.peta', ['kampungs' => $kampungs, 'terpilih' => $kampung->slug])</div>
                    <p class="src">Batas kampung dari BIG, bersifat indikatif.</p>
                </div>
            </aside>
        </div>
    </div></div>
@endsection
