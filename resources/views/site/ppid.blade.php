@extends('site.layout')
@php
    $aktif = $kategoriList[$kategori] ?? null;
    $param = fn (array $tambah = []) => array_filter(array_merge(['kategori' => $aktif[0] ?? null, 'jenis' => isset($jenisList[$jenis]) ? $jenis : null, 'tahun' => $tahun], $tambah));
@endphp
@section('judul', $aktif ? $aktif[1] . ' · PPID' : 'PPID')
@section('deskripsi', $aktif ? $aktif[2] : 'Dokumen publik Kantor Camat Long Bagun: informasi berkala, serta merta, setiap saat, dan dikecualikan.')

@section('isi')
    @include('site.partials.phero', [
        'judul' => $aktif ? $aktif[1] : 'Dokumen publik (PPID)',
        'lead' => $aktif ? $aktif[2] : 'Informasi publik Kantor Camat Long Bagun, dikelompokkan menurut klasifikasinya.',
        'crumbs' => $aktif ? [['PPID', route('ppid.index')], [$aktif[1], null]] : [['PPID', null]],
    ])
    <div class="page"><div class="in">
        <div class="pkat">
            @foreach ($kategoriList as $kunci => [$slug, $nama, $uraian])
                <a href="{{ route('ppid.kategori', $slug) }}" @class(['on' => $kategori === $kunci]) @if ($kategori === $kunci) aria-current="page" @endif>
                    <b>{{ $nama }}</b>
                    <span>{{ $uraian }}</span>
                    <small>{{ $jumlah[$kunci] ?? 0 }} dokumen</small>
                </a>
            @endforeach
        </div>

        <form class="filters" method="get" action="{{ $aktif ? route('ppid.kategori', $aktif[0]) : route('ppid.index') }}">
            @if ($aktif)<a href="{{ route('ppid.index') }}">Semua kategori</a>@endif
            <a href="{{ route($aktif ? 'ppid.kategori' : 'ppid.index', $param(['jenis' => null])) }}" @class(['on' => ! isset($jenisList[$jenis])])>Semua jenis</a>
            @foreach ($jenisList as $kunci => $nama)
                <a href="{{ route($aktif ? 'ppid.kategori' : 'ppid.index', $param(['jenis' => $kunci])) }}" @class(['on' => $jenis === $kunci])>{{ $nama }}</a>
            @endforeach
            @if ($tahunList->isNotEmpty())
                @if (isset($jenisList[$jenis]))<input type="hidden" name="jenis" value="{{ $jenis }}">@endif
                <label class="sr-only" for="tahun">Tahun</label>
                <select id="tahun" name="tahun" onchange="this.form.submit()" style="margin-left:auto">
                    <option value="">Semua tahun</option>
                    @foreach ($tahunList as $t)
                        <option value="{{ $t }}" @selected((string) $tahun === (string) $t)>{{ $t }}</option>
                    @endforeach
                </select>
                <noscript><button class="on" type="submit">Terapkan</button></noscript>
            @endif
        </form>

        <div class="rows">
            @forelse ($dokumens as $d)
                <div class="rowi">
                    <div class="when" style="width:auto;padding:8px 12px"><b style="font-size:15px">{{ $d->tahun ?: '–' }}</b></div>
                    <div class="grow"><h3>{{ $d->judul }}</h3><span class="small muted">@if ($d->kategori_label){{ $d->kategori_label }} · @endif{{ $d->jenis_label }}@if ($d->tanggal_unggah) · diunggah {{ $d->tanggal_unggah->translatedFormat('j F Y') }}@endif</span></div>
                    @if ($berkas = Situs::berkas($d->berkas))
                        <a class="btn sm" href="{{ $berkas }}" target="_blank" rel="noopener">@include('site.partials.ikon', ['n' => 'unduh', 's' => 16])Unduh PDF</a>
                    @else
                        <span class="small muted">Berkas belum diunggah</span>
                    @endif
                </div>
            @empty
                <div class="card" style="text-align:center"><p class="muted" style="margin:0">
                    @if ($aktif)
                        Belum ada dokumen pada {{ Str::lower($aktif[1]) }}: {!! Situs::isi(null, 'Dokumen PPID') !!}. Dokumen yang diunggah admin dengan kategori ini tampil di sini.
                    @else
                        Belum ada dokumen: {!! Situs::isi(null, 'Dokumen PPID') !!}. Renstra, Renja, LKjIP, perjanjian kinerja, dan SOP yang diunggah admin tampil di sini.
                    @endif
                </p></div>
            @endforelse
        </div>

        {{ $dokumens->links('site.partials.pager') }}
    </div></div>
@endsection
