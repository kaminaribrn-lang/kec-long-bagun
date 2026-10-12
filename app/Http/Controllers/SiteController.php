<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Aparatur;
use App\Models\Destinasi;
use App\Models\DynamicPage;
use App\Models\DokumenPublik;
use App\Models\Gallery;
use App\Models\Kampung;
use App\Models\Layanan;
use App\Models\Pengaduan;
use App\Models\Post;
use App\Models\Slider;
use App\Models\Statistik;
use App\Support\Halaman;
use App\Support\Situs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Halaman depan situs Kecamatan Long Bagun. Semua isi dibaca dari koleksi
 * yang dikelola di panel admin; tidak ada isi yang ditulis mati di tampilan.
 */
class SiteController extends Controller
{
    /** Templat modul Halaman Dinamis dan metode yang menampilkannya. */
    private const MODUL = [
        'berita' => 'beritaIndex',
        'modul_kampung' => 'kampungIndex',
        'modul_geografi' => 'geografi',
        'modul_layanan' => 'layananIndex',
        'modul_formulir' => 'formulir',
        'modul_pengaduan' => 'pengaduan',
        'modul_data' => 'dataIndex',
        'modul_wisata' => 'wisataIndex',
        'modul_akses' => 'akses',
        'modul_pengumuman' => 'pengumumanIndex',
        'modul_agenda' => 'agenda',
        'modul_galeri' => 'galeri',
        'modul_ppid' => 'ppid',
        'modul_kontak' => 'kontak',
    ];

    /** Halaman dinamis yang sedang ditampilkan lewat halaman(); null untuk rute tetap. */
    private ?DynamicPage $halamanAktif = null;

    private const TOPIK = [
        'kependudukan' => 'Kependudukan',
        'pendidikan' => 'Pendidikan',
        'kesehatan' => 'Kesehatan',
        'ekonomi' => 'Ekonomi',
    ];

    private const KELOMPOK = [
        'penduduk_per_tahun' => 'Penduduk per tahun',
        'agama' => 'Penduduk menurut agama',
    ];

    public function home()
    {
        // Agenda beranda selalu lima baris: yang mendatang, sisanya diisi agenda terakhir yang sudah lewat (tampil di atasnya).
        $agenda = $this->agendaMendatang()->take(5)->get();
        $agendaLalu = $this->agendaLalu()->take(5 - $agenda->count())->get();

        return view('site.home', [
            'agendaLalu' => $agendaLalu,
            'agendaAdaLagi' => $this->agendaLalu()->count() > $agendaLalu->count(),
            'kunci' => Statistik::where('topik', 'kunci')->orderBy('urutan')->get(),
            'layanans' => Layanan::where('status', true)->where('tampil_beranda', true)->orderBy('urutan')->take(4)->get(),
            'berita' => $this->berita()->take(5)->get(),
            'pengumuman' => $this->pengumuman()->take(3)->get(),
            'agenda' => $agenda,
            'sliders' => Slider::with('file')->where('status', 1)->orderByDesc('is_pinned')->latest()->take(8)->get(),
            'kampungs' => Kampung::where('status', true)->orderByDesc('penduduk')->get(),
            'destinasis' => Destinasi::where('status', true)->orderBy('urutan')->take(3)->get(),
            'camat' => Aparatur::where('status', true)->orderBy('order')->first(),
        ]);
    }

    // ---------- Halaman Dinamis ----------

    /**
     * Semua alamat yang tidak punya rute tetap: dicari di Menu Website dan Halaman Dinamis.
     * Templat modul diteruskan ke metode modulnya; templat isi ditampilkan lewat site.dinamis.show.
     */
    public function halaman(Request $request)
    {
        $page = Halaman::untukPath($request->getPathInfo());

        abort_if(! $page || ! $page->published, 404);

        if (isset(self::MODUL[$page->template])) {
            $this->halamanAktif = $page;

            return app()->call([$this, self::MODUL[$page->template]]);
        }

        $pegawai = $page->template === 'aparatur' ? Aparatur::where('status', true)->orderBy('order')->get() : collect();

        return view('site.dinamis.show', [
            'halaman' => $page,
            'isi' => is_array($page->content) ? $page->content : [],
            'induk' => $page->menu?->parent,
            'pegawai' => $pegawai,
        ]);
    }

    /** Halaman dinamis untuk alamat ini (bila admin membuatnya), dipakai modul untuk judul, pengantar, dan SEO. */
    private function halamanIni(): ?DynamicPage
    {
        $page = $this->halamanAktif ?? Halaman::untukPath(request()->getPathInfo());

        abort_if($page && ! $page->published, 404);

        return $page;
    }

    public function geografi()
    {
        return view('site.profil.geografi', [
            'halaman' => $this->halamanIni(),
            'kampungs' => Kampung::where('status', true)->orderByDesc('penduduk')->get(),
            'kunci' => Statistik::where('topik', 'kunci')->orderBy('urutan')->get(),
        ]);
    }

    // ---------- Kampung ----------

    public function kampungIndex(Request $request)
    {
        $urut = $request->query('urut') === 'nama' ? 'nama' : 'penduduk';

        return view('site.kampung.index', [
            'halaman' => $this->halamanIni(),
            'urut' => $urut,
            'kampungs' => Kampung::where('status', true)
                ->when($urut === 'nama', fn ($q) => $q->orderBy('nama'), fn ($q) => $q->orderByDesc('penduduk'))
                ->get(),
        ]);
    }

    public function kampungShow(string $slug)
    {
        $kampung = Kampung::where('status', true)->where('slug', $slug)->firstOrFail();

        return view('site.kampung.show', [
            'kampung' => $kampung,
            'kampungs' => Kampung::where('status', true)->orderByDesc('penduduk')->get(),
            'destinasis' => $kampung->destinasis()->where('status', true)->orderBy('urutan')->get(),
        ]);
    }

    // ---------- Layanan ----------

    public function layananIndex(Request $request)
    {
        $kategori = $request->query('kategori');

        return view('site.layanan.index', [
            'halaman' => $this->halamanIni(),
            'kategori' => $kategori,
            'kategoris' => Layanan::where('status', true)->whereNotNull('kategori')->distinct()->orderBy('kategori')->pluck('kategori'),
            'layanans' => Layanan::where('status', true)
                ->when($kategori, fn ($q) => $q->where('kategori', $kategori))
                ->orderBy('urutan')->orderBy('nama')->get(),
        ]);
    }

    public function layananShow(string $slug)
    {
        return view('site.layanan.show', [
            'layanan' => Layanan::where('status', true)->where('slug', $slug)->firstOrFail(),
        ]);
    }

    public function formulir()
    {
        return view('site.layanan.formulir', [
            'halaman' => $this->halamanIni(),
            'layanans' => Layanan::where('status', true)->whereNotNull('formulir')->orderBy('urutan')->get(),
        ]);
    }

    public function pengaduan()
    {
        return view('site.layanan.pengaduan', [
            'halaman' => $this->halamanIni(),
            'kampungs' => Kampung::where('status', true)->orderBy('nama')->pluck('nama'),
        ]);
    }

    public function pengaduanStore(Request $request)
    {
        // Kolom jebakan: manusia tidak melihatnya, bot biasanya mengisinya.
        if (filled($request->input('situs_web'))) {
            return back()->with('terkirim', 'PGD-000000');
        }

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:120'],
            'kampung' => ['required', 'string', 'max:120'],
            'kontak' => ['required', 'string', 'max:60', 'regex:/^[0-9+\-\s()]{8,}$/'],
            'isi' => ['required', 'string', 'min:20', 'max:5000'],
        ], [
            'required' => ':attribute wajib diisi.',
            'kontak.regex' => 'Nomor kontak hanya boleh berisi angka, minimal 8 digit.',
            'isi.min' => 'Isi aduan minimal 20 karakter agar bisa ditindaklanjuti.',
        ], [
            'nama' => 'Nama', 'kampung' => 'Kampung', 'kontak' => 'Nomor kontak', 'isi' => 'Isi aduan',
        ]);

        $aduan = Pengaduan::create([
            'kode_laporan' => Pengaduan::generateKodeLaporan(),
            'kategori' => 'Pengaduan warga',
            'nama_lengkap' => strip_tags($data['nama']),
            'telepon' => $data['kontak'],
            'kampung' => strip_tags($data['kampung']),
            'judul_laporan' => Str::limit(strip_tags($data['isi']), 80),
            'kronologis' => strip_tags($data['isi']),
            'status' => 'baru',
        ]);

        $tujuan = Situs::pengaturan()->email;
        if (filled($tujuan)) {
            try {
                Mail::raw(
                    "Pengaduan baru dari situs Kecamatan Long Bagun\n\n"
                    . "Kode: {$aduan->kode_laporan}\nNama: {$aduan->nama_lengkap}\nKampung: {$aduan->kampung}\nKontak: {$aduan->telepon}\n\n"
                    . "Isi aduan:\n{$aduan->kronologis}\n",
                    fn ($m) => $m->to($tujuan)->subject("Pengaduan warga {$aduan->kode_laporan}")
                );
            } catch (\Throwable $e) {
                // Aduan tetap tersimpan di panel admin walau email gagal terkirim.
                Log::error('Email pengaduan gagal dikirim', ['kode' => $aduan->kode_laporan, 'error' => $e->getMessage()]);
            }
        }

        return back()->with('terkirim', $aduan->kode_laporan);
    }

    // ---------- Data ----------

    public function dataIndex()
    {
        return view('site.data.index', [
            'halaman' => $this->halamanIni(),
            'kunci' => Statistik::where('topik', 'kunci')->orderBy('urutan')->get(),
            'topiks' => self::TOPIK,
            'jumlah' => Statistik::whereIn('topik', array_keys(self::TOPIK))->selectRaw('topik, count(*) as n')->groupBy('topik')->pluck('n', 'topik'),
        ]);
    }

    public function dataTopik(string $topik)
    {
        abort_unless(isset(self::TOPIK[$topik]), 404);

        return view('site.data.topik', [
            'topik' => $topik,
            'judul' => self::TOPIK[$topik],
            'kelompoks' => Statistik::where('topik', $topik)->orderBy('urutan')->get()->groupBy('kelompok'),
            'labelKelompok' => self::KELOMPOK,
            'kampungs' => $topik === 'kependudukan' ? Kampung::where('status', true)->orderByDesc('penduduk')->get() : collect(),
        ]);
    }

    public function dataCsv(string $kelompok)
    {
        if ($kelompok === 'penduduk-per-kampung') {
            $baris = Kampung::where('status', true)->orderByDesc('penduduk')->get()
                ->map(fn ($k) => [$k->nama, $k->kode_wilayah, $k->luas_km2, $k->penduduk, $k->jumlah_rt, $k->tahun_data, $k->sumber_data]);
            $kepala = ['Kampung', 'Kode wilayah', 'Luas (km2)', 'Penduduk', 'RT', 'Tahun', 'Sumber'];
        } else {
            $kunci = str_replace('-', '_', $kelompok);
            $data = Statistik::where('kelompok', $kunci)->orderBy('urutan')->get();
            abort_if($data->isEmpty(), 404);
            $baris = $data->map(fn ($s) => [$s->label, $s->nilai, $s->satuan, $s->tahun, $s->keterangan, $s->sumber]);
            $kepala = ['Label', 'Nilai', 'Satuan', 'Tahun', 'Keterangan', 'Sumber'];
        }

        return response()->streamDownload(function () use ($kepala, $baris) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8
            fputcsv($out, $kepala, ';');
            foreach ($baris as $b) {
                fputcsv($out, $b, ';');
            }
            fclose($out);
        }, "long-bagun-{$kelompok}.csv", ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    // ---------- Wisata ----------

    public function wisataIndex(Request $request)
    {
        $jenis = $request->query('jenis');

        return view('site.wisata.index', [
            'halaman' => $this->halamanIni(),
            'jenis' => $jenis,
            'jenisList' => Destinasi::JENIS,
            'destinasis' => Destinasi::with('kampung')->where('status', true)
                ->when(isset(Destinasi::JENIS[$jenis]), fn ($q) => $q->where('jenis', $jenis))
                ->orderBy('urutan')->get(),
        ]);
    }

    public function wisataShow(string $slug)
    {
        return view('site.wisata.show', [
            'destinasi' => Destinasi::with('kampung')->where('status', true)->where('slug', $slug)->firstOrFail(),
        ]);
    }

    public function akses()
    {
        return view('site.wisata.akses', ['halaman' => $this->halamanIni()]);
    }

    // ---------- Informasi ----------

    public function beritaIndex(Request $request)
    {
        $kategori = $request->query('kategori');

        return view('site.berita.index', [
            'halaman' => $this->halamanIni(),
            'jenis' => 'berita',
            'kategori' => $kategori,
            'kategoris' => \App\Models\PostCategory::where('active', 1)->where('slug', '!=', 'pengumuman')->whereHas('posts', fn ($q) => $q->where('status', 1))->orderBy('name')->get(),
            'posts' => $this->berita()->when($kategori, fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $kategori)))->paginate(12)->withQueryString(),
        ]);
    }

    public function beritaShow(string $slug)
    {
        $post = $this->berita()->where('slug', $slug)->firstOrFail();
        $post->increment('read');

        return view('site.berita.show', [
            'jenis' => 'berita',
            'post' => $post,
            'lainnya' => $this->berita()->where('id', '!=', $post->id)->take(3)->get(),
        ]);
    }

    public function pengumumanIndex()
    {
        return view('site.berita.index', [
            'halaman' => $this->halamanIni(),
            'jenis' => 'pengumuman',
            'kategori' => null,
            'kategoris' => collect(),
            'posts' => $this->pengumuman(false)->paginate(12),
        ]);
    }

    public function pengumumanShow(string $slug)
    {
        $post = $this->pengumuman(false)->where('slug', $slug)->firstOrFail();
        $post->increment('read');

        return view('site.berita.show', [
            'jenis' => 'pengumuman',
            'post' => $post,
            'lainnya' => $this->pengumuman()->where('id', '!=', $post->id)->take(3)->get(),
        ]);
    }

    public function agenda()
    {
        return view('site.agenda', [
            'halaman' => $this->halamanIni(),
            'mendatang' => $this->agendaMendatang()->get(),
            'arsip' => $this->agendaLalu()->take(30)->get(),
        ]);
    }

    /** Lima agenda yang sudah lewat berikutnya untuk tombol "Muat agenda sebelumnya" di beranda. */
    public function agendaSebelumnya(Request $request)
    {
        $offset = max(0, (int) $request->query('offset'));
        $rows = $this->agendaLalu()->skip($offset)->take(6)->get();

        return response()->json([
            'html' => view('site.partials.agenda-baris', ['items' => $rows->take(5)->reverse(), 'lalu' => true])->render(),
            'offset' => $offset + min(5, $rows->count()),
            'habis' => $rows->count() <= 5,
        ]);
    }

    public function galeri()
    {
        return view('site.galeri', [
            'halaman' => $this->halamanIni(),
            'kelompoks' => Gallery::where('is_active', true)->get()->groupBy(fn ($g) => $g->kegiatan ?: 'Lainnya'),
        ]);
    }

    // ---------- PPID, kontak, pencarian ----------

    public function ppid(Request $request, ?string $kategori = null)
    {
        $jenis = $request->query('jenis');
        $tahun = $request->query('tahun');
        $kategori = DokumenPublik::kategoriDariAlamat($kategori);

        return view('site.ppid', [
            'halaman' => $this->halamanIni(),
            'jenis' => $jenis,
            'tahun' => $tahun,
            'jenisList' => DokumenPublik::JENIS,
            'kategori' => $kategori,
            'kategoriList' => DokumenPublik::KATEGORI,
            'jumlah' => DokumenPublik::where('status', true)->whereNotNull('kategori')->selectRaw('kategori, count(*) as n')->groupBy('kategori')->pluck('n', 'kategori'),
            'tahunList' => DokumenPublik::where('status', true)->whereNotNull('tahun')->distinct()->orderByDesc('tahun')->pluck('tahun'),
            'dokumens' => DokumenPublik::where('status', true)
                ->when($kategori, fn ($q) => $q->where('kategori', $kategori))
                ->when(isset(DokumenPublik::JENIS[$jenis]), fn ($q) => $q->where('jenis', $jenis))
                ->when(is_numeric($tahun), fn ($q) => $q->where('tahun', (int) $tahun))
                ->orderByDesc('tahun')->orderByDesc('tanggal_unggah')->orderBy('id')->paginate(30)->withQueryString(),
        ]);
    }

    public function kontak()
    {
        return view('site.kontak', ['halaman' => $this->halamanIni()]);
    }

    public function cari(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $hasil = collect();

        if (mb_strlen($q) >= 2) {
            $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $q) . '%';

            $hasil = collect()
                ->concat(Layanan::where('status', true)->where(fn ($w) => $w->where('nama', 'like', $like)->orWhere('ringkasan', 'like', $like))->take(10)->get()
                    ->map(fn ($l) => ['Layanan', $l->nama, $l->ringkasan, route('layanan.show', $l->slug)]))
                ->concat(Kampung::where('status', true)->where(fn ($w) => $w->where('nama', 'like', $like)->orWhere('uraian', 'like', $like))->take(11)->get()
                    ->map(fn ($k) => ['Kampung', $k->nama, $k->uraian, route('kampung.show', $k->slug)]))
                ->concat($this->berita()->where(fn ($w) => $w->where('title', 'like', $like)->orWhere('content', 'like', $like))->take(10)->get()
                    ->map(fn ($p) => ['Berita', $p->title, $p->ringkasan ?: Str::limit(strip_tags((string) $p->content), 140), route('berita.show', $p->slug)]))
                ->concat($this->pengumuman(false)->where(fn ($w) => $w->where('title', 'like', $like)->orWhere('content', 'like', $like))->take(10)->get()
                    ->map(fn ($p) => ['Pengumuman', $p->title, Str::limit(strip_tags((string) $p->content), 140), route('pengumuman.show', $p->slug)]))
                ->concat(Destinasi::where('status', true)->where(fn ($w) => $w->where('nama', 'like', $like)->orWhere('uraian', 'like', $like))->take(10)->get()
                    ->map(fn ($d) => ['Wisata', $d->nama, $d->uraian, route('wisata.show', $d->slug)]))
                ->concat(DokumenPublik::where('status', true)->where('judul', 'like', $like)->take(10)->get()
                    ->map(fn ($d) => ['Dokumen', $d->judul, $d->jenis_label . ($d->tahun ? ' ' . $d->tahun : ''), route('ppid.index', ['jenis' => $d->jenis])]));
        }

        return view('site.cari', ['q' => $q, 'hasil' => $hasil]);
    }

    public function sitemap()
    {
        $urls = collect([route('beranda')])
            ->concat(Halaman::menu()->pluck('link')->filter(fn ($l) => filled($l) && $l !== '#' && ! Str::startsWith($l, 'http'))->map(fn ($l) => url($l)))
            ->concat(DynamicPage::where('published', true)->whereNull('menu_id')->pluck('slug')->map(fn ($slug) => url('/halaman/' . $slug)))
            ->concat(collect(array_keys(self::TOPIK))->map(fn ($s) => route('data.topik', $s)))
            ->concat(Kampung::where('status', true)->pluck('slug')->map(fn ($s) => route('kampung.show', $s)))
            ->concat(Layanan::where('status', true)->pluck('slug')->map(fn ($s) => route('layanan.show', $s)))
            ->concat(Destinasi::where('status', true)->pluck('slug')->map(fn ($s) => route('wisata.show', $s)))
            ->concat($this->berita()->pluck('slug')->map(fn ($s) => route('berita.show', $s)))
            ->concat($this->pengumuman(false)->pluck('slug')->map(fn ($s) => route('pengumuman.show', $s)))
            ->unique()->values();

        return response()->view('site.sitemap', ['urls' => $urls])->header('Content-Type', 'application/xml');
    }

    // ---------- Kueri bersama ----------

    private function terbit(): Builder
    {
        return Post::query()->with(['category', 'file'])
            ->where('type', 'post') // panel admin Berita hanya mengelola tipe ini
            ->where('status', 1)
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->orderByDesc('published_at')->orderByDesc('id');
    }

    private function berita(): Builder
    {
        return $this->terbit()->where(fn ($q) => $q->whereNull('category_id')->orWhereHas('category', fn ($c) => $c->where('slug', '!=', 'pengumuman')));
    }

    /** Pengumuman; secara bawaan hanya yang belum melewati tanggal berakhir. */
    private function pengumuman(bool $hanyaAktif = true): Builder
    {
        return $this->terbit()
            ->whereHas('category', fn ($c) => $c->where('slug', 'pengumuman'))
            ->when($hanyaAktif, fn ($q) => $q->where(fn ($w) => $w->whereNull('berakhir_at')->orWhere('berakhir_at', '>=', now()->toDateString())));
    }

    private function agendaMendatang(): Builder
    {
        return Agenda::query()->whereNotNull('schedule')
            ->whereRaw('COALESCE(end_at, schedule) >= ?', [now()->startOfDay()])
            ->orderBy('schedule');
    }

    private function agendaLalu(): Builder
    {
        return Agenda::query()->whereNotNull('schedule')
            ->whereRaw('COALESCE(end_at, schedule) < ?', [now()->startOfDay()])
            ->orderByDesc('schedule');
    }
}
