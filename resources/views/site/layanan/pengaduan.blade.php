@extends('site.layout')
@section('judul', 'Pengaduan warga')
@section('deskripsi', 'Sampaikan keluhan atau masukan kepada Kantor Camat Long Bagun.')

@section('isi')
    @include('site.partials.phero', ['judul' => 'Pengaduan warga', 'lead' => 'Sampaikan keluhan atau masukan. Pesan Anda masuk ke kantor camat dan tidak ditampilkan ke publik.', 'crumbs' => [['Layanan', route('layanan.index')], ['Pengaduan', null]]])
    <div class="page"><div class="in">
        <div class="split">
            <div class="card">
                @if (session('terkirim'))
                    <div class="notice ok" role="status">
                        <b>Pengaduan terkirim.</b> Kode tanda terima Anda: <b>{{ session('terkirim') }}</b>. Simpan kode ini bila perlu menanyakan tindak lanjut ke kantor camat.
                    </div>
                @endif
                @if ($errors->any())
                    <div class="notice bad" role="alert">Pengaduan belum terkirim. Periksa isian yang ditandai di bawah.</div>
                @endif

                <form method="post" action="{{ route('layanan.pengaduan.store') }}" novalidate>
                    @csrf
                    <div class="hp" aria-hidden="true"><label for="situs_web">Kosongkan kolom ini</label><input type="text" id="situs_web" name="situs_web" tabindex="-1" autocomplete="off"></div>

                    <div class="field">
                        <label for="nama">Nama</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required maxlength="120" autocomplete="name">
                        @error('nama')<span class="err">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="kampung">Kampung</label>
                        <select id="kampung" name="kampung" required>
                            <option value="">Pilih kampung</option>
                            @foreach ($kampungs as $k)
                                <option value="{{ $k }}" @selected(old('kampung') === $k)>{{ $k }}</option>
                            @endforeach
                            <option value="Luar Kecamatan Long Bagun" @selected(old('kampung') === 'Luar Kecamatan Long Bagun')>Luar Kecamatan Long Bagun</option>
                        </select>
                        @error('kampung')<span class="err">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="kontak">Nomor kontak (telepon atau WhatsApp)</label>
                        <input type="tel" id="kontak" name="kontak" value="{{ old('kontak') }}" required maxlength="60" autocomplete="tel" inputmode="tel">
                        @error('kontak')<span class="err">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="isi">Isi aduan</label>
                        <textarea id="isi" name="isi" required maxlength="5000">{{ old('isi') }}</textarea>
                        @error('isi')<span class="err">{{ $message }}</span>@enderror
                    </div>
                    <button class="btn" type="submit">Kirim pengaduan</button>
                </form>
            </div>
            <aside class="grid" style="gap:20px">
                <div class="card em">
                    <h3>Data yang kami minta</h3>
                    <p class="small" style="margin:0">Hanya nama, kampung, nomor kontak, dan isi aduan. Data ini dipakai untuk menindaklanjuti aduan dan tidak ditampilkan ke publik.</p>
                </div>
                <div class="card">
                    <h3>Kanal lain</h3>
                    <dl class="facts">
                        <div><dt>Telepon</dt><dd>{!! Situs::isi($site->phone, 'Nomor telepon') !!}</dd></div>
                        <div><dt>WhatsApp</dt><dd>{!! Situs::isi($site->whatsapp, 'Nomor WhatsApp') !!}</dd></div>
                        <div><dt>Email</dt><dd>{!! Situs::isi($site->email, 'Email resmi') !!}</dd></div>
                    </dl>
                    <a class="more" style="margin-top:14px" href="https://www.lapor.go.id/" target="_blank" rel="noopener">Lapor lewat SP4N-LAPOR</a>
                </div>
            </aside>
        </div>
    </div></div>
@endsection
