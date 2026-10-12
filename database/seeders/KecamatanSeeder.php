<?php

namespace Database\Seeders;

use App\Models\Agenda;
use App\Models\Aparatur;
use App\Models\Destinasi;
use App\Models\DokumenPublik;
use App\Models\Kampung;
use App\Models\Layanan;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Statistik;
use App\Models\User;
use App\Models\WebsiteIdentity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Isi awal situs Kecamatan Long Bagun, persis dari prd.md bagian 6.
 * Data yang belum ada sengaja dikosongkan: halaman depan menampilkannya
 * sebagai teks pengganti dalam kurung siku sampai diisi dari panel admin.
 */
class KecamatanSeeder extends Seeder
{
    private const SUMBER = 'BPS Mahakam Ulu';
    private const TAUTAN_BPS = 'https://mahulukab.bps.go.id/id/publication/2025/09/26/e4611161ca31da0dea3d32a2/kecamatan-long-bagun-dalam-angka-2025.html';

    public function run(): void
    {
        $this->pengaturan();
        $kampung = $this->kampung();
        $this->statistik();
        $this->layanan();
        $this->destinasi($kampung);
        $this->pegawai();
        $this->berita();
        $this->agenda();
        $this->dokumen();
    }

    private function pengaturan(): void
    {
        $site = WebsiteIdentity::query()->latest('id')->first() ?? new WebsiteIdentity();

        $site->fill([
            'name' => 'Kecamatan Long Bagun',
            'description' => 'Situs resmi Kantor Camat Long Bagun, Kabupaten Mahakam Ulu, Kalimantan Timur.',
            'welcome_title' => 'Kecamatan Long Bagun',
            'welcome_subtitle' => 'Syarat layanan, data sebelas kampung, berita, dan dokumen publik Kantor Camat Long Bagun dalam satu tempat.',
            'latitude' => 0.5496,
            'longitude' => 115.2575,
            'alert_active' => false,
            'alert_level' => 'info',
            'akses' => [
                ['jalur' => 'Sungai', 'isi' => 'Kapal penumpang dari Samarinda dan speedboat dari Tering (Kutai Barat). Saat air dangkal, kapal dari Samarinda hanya sampai Long Iram.'],
                ['jalur' => 'Darat', 'isi' => 'Ruas Tering–Long Bagun sekitar 143 km. Pemprov Kaltim menargetkan sisa 7,65 km dari 31,53 km yang ditanganinya tuntas pada 2026. Samarinda–Ujoh Bilang 14 sampai 15 jam menurut catatan 2021.'],
                ['jalur' => 'Udara', 'isi' => 'Bandara Ujoh Bilang dalam pembangunan, landasan pacu 750 m x 23 m, target operasi 2026 atau setelahnya.'],
            ],
            'akses_catatan' => 'Kecamatan ini rawan banjir luapan Sungai Mahakam; pos pantau tinggi muka air berada di Jembatan Pelabuhan Ujoh Bilang.',
            'akses_diperbarui' => '2026-10-08',
            'footer_links_related' => [
                ['label' => 'Pemkab Mahakam Ulu', 'url' => 'https://mahakamulukab.go.id/'],
                ['label' => 'SP4N-LAPOR', 'url' => 'https://www.lapor.go.id/'],
                ['label' => 'BPS Mahakam Ulu', 'url' => 'https://mahulukab.bps.go.id/'],
            ],
        ])->save();
    }

    /** @return array<string, Kampung> */
    private function kampung(): array
    {
        // nama, slug, kode wilayah, luas km2, penduduk 2024, RT, uraian singkat dari catatan PRD
        $rows = [
            ['Ujoh Bilang', 'ujoh-bilang', '64.11.01.2006', 354.73, 5474, 15, 'Ibu kota Kecamatan Long Bagun dan ibu kota Kabupaten Mahakam Ulu.'],
            ['Long Melaham', 'long-melaham', '64.11.01.2002', 102.44, 2198, 5, 'Batu Dinding berada di kampung ini.'],
            ['Long Bagun Ulu', 'long-bagun-ulu', '64.11.01.2008', 567.04, 1693, 6, null],
            ['Mamahak Ilir', 'mamahak-ilir', '64.11.01.2003', 638.81, 1541, 5, null],
            ['Batu Majang', 'batu-majang', '64.11.01.2005', 135.54, 1215, 7, 'Permukiman Dayak Kenyah di pertemuan Sungai Alan dan Sungai Mahakam.'],
            ['Long Bagun Ilir', 'long-bagun-ilir', '64.11.01.2007', 74.63, 1160, 4, 'Tuan rumah Festival Omin Atun Onam Adet 2026.'],
            ['Batoq Kelo', 'batoq-kelo', '64.11.01.2009', 1530.76, 1046, 4, null],
            ['Rukun Damai', 'rukun-damai', '64.11.01.2011', 12.30, 993, 5, null],
            ['Long Merah', 'long-merah', '64.11.01.2010', 311.04, 540, 2, null],
            ['Long Hurai', 'long-hurai', '64.11.01.2001', 41.18, 437, 2, null],
            ['Mamahak Ulu', 'mamahak-ulu', '64.11.01.2004', 996.28, 360, 2, null],
        ];

        $catatan = [
            'mamahak-ilir' => 'Ejaan lain yang beredar: Memahak Ilir atau Mamahak Besar.',
            'batoq-kelo' => 'Ejaan lain yang beredar: Batoq Kelau.',
        ];

        $hasil = [];
        foreach ($rows as [$nama, $slug, $kode, $luas, $penduduk, $rt, $uraian]) {
            $hasil[$slug] = Kampung::updateOrCreate(['slug' => $slug], [
                'nama' => $nama,
                'kode_wilayah' => $kode,
                'luas_km2' => $luas,
                'penduduk' => $penduduk,
                'tahun_data' => 2024,
                'sumber_data' => self::SUMBER,
                'jumlah_rt' => $rt,
                'uraian' => $uraian,
                'catatan_admin' => $catatan[$slug] ?? null,
                'status' => true,
            ]);
        }

        // Titik kantor camat (PRD 6.1) berada di Ujoh Bilang.
        $hasil['ujoh-bilang']->update(['latitude' => 0.5496, 'longitude' => 115.2575]);

        return $hasil;
    }

    private function statistik(): void
    {
        $rows = [
            // topik, kelompok, label, nilai, satuan, tahun, keterangan, catatan admin
            ['kunci', 'kunci', 'Kampung', 11, 'kampung', null, null, null],
            ['kunci', 'kunci', 'Penduduk', 17017, 'jiwa', 2025, 'Perkiraan pertengahan tahun', null],
            ['kunci', 'kunci', 'Luas wilayah', 4801.22, 'km²', null, null, 'CEK: sumber lain menyebut 4.971,2 km². Cocokkan ke publikasi BPS sebelum peluncuran.'],
            ['kunci', 'kunci', 'Rukun tetangga', 58, 'RT', null, null, 'CEK: penjumlahan RT per kampung menghasilkan 57. Cocokkan ke publikasi BPS sebelum peluncuran.'],

            ['kependudukan', 'penduduk_per_tahun', '2010', 7886, 'jiwa', 2010, 'Sensus Penduduk 2010', null],
            ['kependudukan', 'penduduk_per_tahun', '2020', 14462, 'jiwa', 2020, 'Sensus Penduduk 2020', null],
            ['kependudukan', 'penduduk_per_tahun', '2024', 16744, 'jiwa', 2024, 'Kepadatan sekitar 3,5 jiwa/km²', null],
            ['kependudukan', 'penduduk_per_tahun', '2025', 17017, 'jiwa', 2025, 'Perkiraan pertengahan tahun', null],

            ['kependudukan', 'agama', 'Katolik', 8899, 'jiwa', 2022, '56,7%', null],
            ['kependudukan', 'agama', 'Islam', 4483, 'jiwa', 2022, '28,5%', null],
            ['kependudukan', 'agama', 'Protestan', 2311, 'jiwa', 2022, '14,7%', null],
            ['kependudukan', 'agama', 'Hindu', 8, 'jiwa', 2022, '0,05%', null],
            ['kependudukan', 'agama', 'Buddha', 2, 'jiwa', 2022, '0,01%', null],
        ];

        foreach ($rows as $i => [$topik, $kelompok, $label, $nilai, $satuan, $tahun, $keterangan, $catatan]) {
            Statistik::updateOrCreate(['topik' => $topik, 'kelompok' => $kelompok, 'label' => $label], [
                'nilai' => $nilai,
                'satuan' => $satuan,
                'tahun' => $tahun,
                'sumber' => self::SUMBER,
                'tautan_sumber' => self::TAUTAN_BPS,
                'keterangan' => $keterangan,
                'catatan_admin' => $catatan,
                'urutan' => $i,
            ]);
        }
    }

    private function layanan(): void
    {
        // Syarat dan alur kependudukan diambil dari Perpres 96/2018 dan Permendagri 108/2019.
        // Waktu, kontak petugas, dan layanan yang bergantung pada aturan daerah menunggu SOP kantor camat (PRD 6.7).
        $adminduk = "Peraturan Presiden Nomor 96 Tahun 2018 tentang Persyaratan dan Tata Cara Pendaftaran Penduduk dan Pencatatan Sipil\n"
            . "Peraturan Menteri Dalam Negeri Nomor 108 Tahun 2019 tentang Peraturan Pelaksanaan Peraturan Presiden Nomor 96 Tahun 2018\n"
            . 'Undang-Undang Nomor 24 Tahun 2013 tentang Perubahan atas Undang-Undang Nomor 23 Tahun 2006 tentang Administrasi Kependudukan, Pasal 79A (tidak dipungut biaya)';

        $rows = [
            ['Pengantar KTP dan KK', 'Kependudukan', 'ktp', [
                'ringkasan' => 'KTP-el dan Kartu Keluarga diterbitkan Disdukcapil Kabupaten Mahakam Ulu tanpa biaya. Peraturan nasional tidak mensyaratkan surat pengantar; peran kantor camat dalam layanan ini mengikuti SOP kantor camat.',
                'syarat' => [
                    'KTP-el baru: sudah berusia 17 tahun, sudah kawin, atau pernah kawin (Perpres 96/2018 Pasal 15)',
                    'KTP-el baru: Kartu Keluarga (Perpres 96/2018 Pasal 15)',
                    'KK baru karena membentuk keluarga: buku nikah/kutipan akta perkawinan atau kutipan akta perceraian (Perpres 96/2018 Pasal 11)',
                    'KK baru bila perkawinan/perceraian belum tercatat: surat pernyataan tanggung jawab mutlak (Permendagri 108/2019 Pasal 10 ayat 2)',
                    'Pisah KK: fotokopi KK lama, dan berumur 17 tahun atau sudah/pernah kawin yang dibuktikan dengan KTP-el (Permendagri 108/2019 Pasal 10 ayat 4)',
                    'KK hilang atau rusak: surat keterangan hilang dari kepolisian atau KK yang rusak, dan KTP-el (Perpres 96/2018 Pasal 13)',
                    'KTP-el hilang atau rusak: surat keterangan hilang dari kepolisian atau KTP-el yang rusak, dan KK (Perpres 96/2018 Pasal 21)',
                ],
                'alur' => [
                    'Isi dan tanda tangani formulir biodata, lalu serahkan bersama persyaratan.',
                    'Petugas memverifikasi dan memvalidasi formulir serta persyaratan.',
                    'Petugas Disdukcapil merekam data ke basis data kependudukan.',
                    'Kepala Disdukcapil menerbitkan dan menandatangani dokumen.',
                    'Dokumen diserahkan kepada penduduk, langsung atau melalui petugas registrasi. (Tata cara penerbitan KK, Permendagri 108/2019 Pasal 17.)',
                ],
                'dasar_hukum' => $adminduk,
                'biaya' => 'Gratis',
            ]],
            ['Surat pindah', 'Kependudukan', 'pindah', [
                'ringkasan' => 'Surat keterangan pindah diterbitkan Disdukcapil daerah asal tanpa biaya. Pindah di dalam Kabupaten Mahakam Ulu cukup dengan penerbitan KK dan KTP-el beralamat baru.',
                'syarat' => [
                    'Kartu Keluarga, ditunjukkan saat mengurus (Perpres 96/2018 Pasal 25 ayat 3)',
                    'Bila menempati rumah yang bukan milik sendiri: surat pernyataan bermeterai dari pemilik rumah bahwa ia tidak keberatan alamatnya dipakai (Permendagri 108/2019 Pasal 28 ayat 1)',
                ],
                'alur' => [
                    'Isi dan tanda tangani formulir biodata, lalu serahkan bersama persyaratan ke Disdukcapil daerah asal.',
                    'Petugas memverifikasi dan memvalidasi berkas, lalu merekam data ke basis data kependudukan.',
                    'Pindah dalam satu kampung, antarkampung, atau antarkecamatan di kabupaten yang sama: Disdukcapil menerbitkan KK, KTP-el, dan/atau KIA beralamat baru (Permendagri 108/2019 Pasal 29).',
                    'Pindah antarkabupaten/kota atau antarprovinsi: Disdukcapil menerbitkan surat keterangan pindah (SKP), yang berlaku 100 hari kerja (Permendagri 108/2019 Pasal 30).',
                    'Laporkan SKP ke Disdukcapil daerah tujuan sebelum masa berlakunya habis untuk mendapat KK dan KTP-el beralamat baru.',
                ],
                'dasar_hukum' => $adminduk,
                'biaya' => 'Gratis',
            ]],
            ['Legalisasi dokumen', 'Administrasi umum', 'stempel', [
                'ringkasan' => 'Syarat dan alur legalisasi di kantor camat ditetapkan oleh aturan daerah dan SOP kantor camat, bukan oleh peraturan nasional.',
            ]],
            ['Rekomendasi izin usaha', 'Perizinan', 'usaha', [
                'ringkasan' => 'Perizinan berusaha diurus lewat sistem OSS. Syarat rekomendasi dari kantor camat ditetapkan oleh aturan daerah dan SOP kantor camat.',
                'dasar_hukum' => 'Peraturan Pemerintah Nomor 28 Tahun 2025 tentang Penyelenggaraan Perizinan Berusaha Berbasis Risiko',
            ]],
        ];

        foreach ($rows as $i => [$nama, $kategori, $ikon, $isi]) {
            Layanan::updateOrCreate(['slug' => Str::slug($nama)], [
                'nama' => $nama,
                'kategori' => $kategori,
                'ikon' => $ikon,
                'tampil_beranda' => true,
                'urutan' => $i,
                'status' => true,
            ] + $isi);
        }
    }

    /** @param array<string, Kampung> $kampung */
    private function destinasi(array $kampung): void
    {
        $rows = [
            ['Batu Dinding', 'batu-dinding', 'alam', 'long-melaham', 'Kampung Long Melaham', 'Tebing batu di tepi Sungai Mahakam, diberitakan setinggi 100 m dan sepanjang 800 m.', null, null],
            ['Kampung Batu Majang', 'kampung-batu-majang', 'budaya', 'batu-majang', 'Kampung Batu Majang', 'Permukiman Dayak Kenyah di pertemuan Sungai Alan dan Sungai Mahakam; namanya dari batu besar yang terpotong aliran Sungai Alan.', null, null],
            ['Riam Udang', 'riam-udang', 'alam', null, 'Sungai Mahakam, arah hulu', 'Jeram yang dilewati perjalanan menuju Long Pahangai dan Long Apari.', null, 'CEK: letak administratifnya perlu dipastikan.'],
            ['Festival Omin Atun Onam Adet', 'omin-atun-onam-adet', 'acara_adat', 'long-bagun-ilir', 'Long Bagun Ilir (2026)', 'Digelar 6 sampai 9 Juni 2026, diikuti 10 kampung sub-suku Aoheng, Seputan, dan Bukot.', null, null],
            ['Hudoq Pekayang', 'hudoq-pekayang', 'acara_adat', null, 'Tingkat kabupaten', 'Upacara tari topeng musim tanam padi.', 'Sekitar September sampai Oktober', null],
            ['Hutan dataran rendah Long Bagun', 'hutan-long-bagun', 'alam', null, 'Hulu Mahakam', 'Blok hutan sekitar 350.000 ha, tercatat sebagai Daerah Penting bagi Burung.', null, null],
        ];

        foreach ($rows as $i => [$nama, $slug, $jenis, $kampungSlug, $lokasi, $uraian, $waktu, $catatan]) {
            Destinasi::updateOrCreate(['slug' => $slug], [
                'nama' => $nama,
                'jenis' => $jenis,
                'kampung_id' => $kampungSlug ? $kampung[$kampungSlug]->id : null,
                'lokasi' => $lokasi,
                'uraian' => $uraian,
                'waktu_terbaik' => $waktu,
                'catatan_admin' => $catatan,
                'urutan' => $i,
                'status' => true,
            ]);
        }
    }

    private function pegawai(): void
    {
        // PRD 6.4: nama camat dari sumber awal 2025 dan wajib dikonfirmasi kantor camat.
        Aparatur::updateOrCreate(['position' => 'Camat Long Bagun'], [
            'name' => 'BONIFASIUS, S.Hut',
            'order' => 1,
            'status' => true,
        ]);
    }

    private function berita(): void
    {
        $user = User::query()->orderBy('id')->first();
        if (! $user) {
            return;
        }

        $budaya = PostCategory::firstOrCreate(['slug' => 'budaya'], ['name' => 'Budaya', 'active' => 1]);
        PostCategory::firstOrCreate(['slug' => 'pengumuman'], ['name' => 'Pengumuman', 'active' => 1]);


        // Rangkuman berita media. Tiap butir: kategori, judul, tanggal terbit sumber,
        // ringkasan, paragraf isi, nama media, judul artikel sumber, dan tautannya.
        $rangkuman = [
            ['pemerintahan', 'Musrenbang Kecamatan Long Bagun 2026 digelar di Ujoh Bilang', '2026-02-07',
                'Hasil Musrenbang Kecamatan Long Bagun pada 6 Februari 2026 menjadi bahan penyusunan RKPD Mahakam Ulu 2027.',
                ['Musrenbang tingkat Kecamatan Long Bagun Tahun 2026 digelar Bappelitbangda Mahakam Ulu pada 6 Februari 2026 di Balai Pertemuan Umum Kampung Ujoh Bilang.',
                    'Sekretaris Bappelitbangda Mahakam Ulu, Franciskus Hului, menyampaikan bahwa usulan yang dibahas merupakan kelanjutan musrenbang kampung dan sudah melewati verifikasi administratif serta peninjauan lapangan. Tidak semua usulan dapat diakomodasi karena keterbatasan kewenangan dan anggaran daerah.',
                    'Kesepakatan forum menjadi bahan penyusunan RKPD Kabupaten Mahakam Ulu 2027 dan diinput melalui SIPD. Berita acara hasil musrenbang ditandatangani Kepala Bappelitbangda, Yohanes Andy Abeh.'],
                'Media Kaltim', 'Musrenbang Long Bagun Jadi Fondasi Penyusunan RKPD Mahulu 2027',
                'https://mediakaltim.com/musrenbang-long-bagun-jadi-fondasi-penyusunan-rkpd-mahulu-2027/'],
            ['kampung', 'Petrus Jumriq resmi menjabat Petinggi Kampung Ujoh Bilang', '2026-08-04',
                'Serah terima jabatan Petinggi Kampung Ujoh Bilang berlangsung di balai adat kampung pada 4 Agustus 2026.',
                ['Serah terima jabatan Petinggi Kampung Ujoh Bilang berlangsung di balai adat kampung pada 4 Agustus 2026. Petrus Jumriq menggantikan penjabat petinggi sebelumnya, Romensius Kuleh.',
                    'Asisten III Pemkab Mahakam Ulu, Kristina Tening, yang hadir dalam acara itu mendorong koordinasi lintas sektor antara pemerintah kampung, pemerintah kabupaten, lembaga kemasyarakatan, dan warga.',
                    'Petrus Jumriq menyatakan akan membuka ruang koordinasi dan dialog dengan pemerintah kabupaten, lembaga kampung, dan warga.'],
                'RRI', 'Sertijab Petinggi Ujoh Bilang Dorong Koordinasi untuk Kemajuan Kampung',
                'https://rri.co.id/sendawar/daerah-3t/2624430/sertijab-petinggi-ujoh-bilang-dorong-koordinasi-untuk-kemajuan-kampung'],
            ['pembangunan', 'DPRD Mahakam Ulu dorong percepatan jalan Long Bagun–Long Apari', '2026-08-12',
                'Ketua DPRD Mahakam Ulu menyatakan sudah berkoordinasi dengan DPRD Kaltim, DPR RI, dan Kementerian PUPR soal jalan darat menuju Long Apari.',
                ['Ketua DPRD Mahakam Ulu, Devung Paran, mendorong percepatan pembangunan jalan poros Long Bagun–Long Apari yang akan membuka akses darat ke wilayah perbatasan.',
                    'Selama ini warga masih bergantung pada angkutan Sungai Mahakam, yang memakan waktu lebih lama dan berpengaruh pada biaya angkut serta harga barang. DPRD Mahakam Ulu menyatakan sudah berkoordinasi dengan DPRD Provinsi Kalimantan Timur, DPR RI, dan Kementerian PUPR.',
                    'Artikel sumber tidak mencantumkan panjang jalan maupun nilai anggarannya.'],
                'RRI', 'Jalan Long Bagun-Long Apari terus Didorong Segera Terealisasi',
                'https://rri.co.id/sendawar/daerah-3t/2644872/jalan-long-bagun-long-apari-terus-didorong-segera-terealisasi'],
            ['kampung', 'Playground anak di Lapangan Cor Ujoh Bilang diresmikan', '2026-08-18',
                'Bupati Mahakam Ulu meresmikan gedung playground anak di Ujoh Bilang pada 17 Agustus 2026, usai upacara HUT ke-81 RI.',
                ['Gedung playground anak di kawasan Lapangan Cor Ujoh Bilang diresmikan pada 17 Agustus 2026, usai upacara HUT ke-81 Republik Indonesia. Peresmian dilakukan Bupati Mahakam Ulu, Angela Idang Belawan.',
                    'Petinggi Kampung Ujoh Bilang, Petrus Jumriq, menjelaskan bahwa gagasan ini berangkat dari kekhawatiran atas anak-anak yang banyak menghabiskan waktu dengan gawai.',
                    'Playground dikelola dengan tarif Rp25.000 per anak per jam dan menampung sekitar 30 sampai 50 anak secara bergantian. Pendapatannya dipakai untuk pemeliharaan dan keamanan.'],
                'RRI', 'Bupati Mahulu Resmikan Playground Ujoh Bilang',
                'https://rri.co.id/samarinda/info-pemda/2660288/bupati-mahulu-resmikan-playground-ujoh-bilang'],
            ['transportasi', 'Kemarau: kapal dari Samarinda hanya sampai hilir Long Iram', '2026-08-23',
                'Debit Sungai Mahakam menyusut; pada 18–22 Agustus 2026 hanya dua kapal tujuan Long Bagun yang transit di Dermaga Aji Imbut.',
                ['Pelayaran kapal dari Samarinda menuju Mahakam Ulu terganggu karena debit Sungai Mahakam menyusut pada musim kemarau. Kapal hanya bisa mencapai wilayah hilir Long Iram, dan perjalanan selanjutnya memakai longboat sekitar 6 sampai 8 jam.',
                    'Koordinator Dermaga Aji Imbut, Tenggarong Seberang, Rusdiansyah, menyebut pada 18–22 Agustus 2026 hanya dua kapal tujuan Long Bagun yang transit. Pada 22 Agustus kapal ke Mahakam Ulu membawa sekitar 18 penumpang dari Samarinda, jauh di bawah kondisi normal 80 sampai 100 penumpang.',
                    'Kondisi sungai berubah dari hari ke hari. Tanyakan jadwal terbaru ke operator kapal sebelum berangkat.'],
                'Niaga.Asia', 'Efek Kemarau, Kapal Rute Mahulu Kini Hanya Sampai Long Iram',
                'https://www.niaga.asia/efek-kemarau-kapal-rute-mahulu-kini-hanya-sampai-long-iram/'],
            ['pembangunan', 'Empat kajian disiapkan untuk Dermaga Batu Dinding di Long Melaham', '2026-09-25',
                'Pemkab Mahakam Ulu membahas rencana induk, DLKr, DLKp, dan Andalalin Dermaga Induk Batu Dinding pada 24–25 September 2026.',
                ['Pemerintah Kabupaten Mahakam Ulu mematangkan pengembangan Dermaga Induk Batu Dinding di Kampung Long Melaham, Kecamatan Long Bagun, dengan empat dokumen: rencana induk, Daerah Lingkungan Kerja (DLKr), Daerah Lingkungan Kepentingan (DLKp), dan Analisis Dampak Lalu Lintas (Andalalin).',
                    'Presentasi laporan antara dibuka Sekretaris Daerah Mahakam Ulu, Stephanus Madang, pada 24 September 2026 di Kantor Bupati dan berlangsung dua hari. Kajian dikerjakan Institut Teknologi Nasional (ITN) Malang dan difasilitasi Dinas Perhubungan.',
                    'Kapasitas dermaga, nilai investasi, dan target pekerjaan fisik belum dirinci dalam artikel sumber.'],
                'Media Kaltim', 'Dermaga Batu Dinding Dimatangkan, Empat Kajian Jadi Dasar Pengembangan',
                'https://mediakaltim.com/dermaga-batu-dinding-dimatangkan-empat-kajian-jadi-dasar-pengembangan/'],
            ['pengumuman', 'Pemilihan petinggi serentak 2026: Ujoh Bilang, Long Hurai, dan Long Merah ikut serta', '2026-02-19',
                'DPMK Mahakam Ulu menyebut tiga kampung di Kecamatan Long Bagun termasuk dalam 23 kampung peserta pemilihan petinggi serentak 2026.',
                ['Dinas Pemberdayaan Masyarakat dan Kampung (DPMK) Mahakam Ulu menyatakan pemilihan petinggi kampung serentak 2026 digelar di 23 kampung di lima kecamatan. Di Kecamatan Long Bagun, pesertanya adalah Kampung Ujoh Bilang, Long Hurai, dan Long Merah.',
                    'Kepala Bidang Pemerintahan Kampung DPMK, Yohanes Belawan, pada 18 Februari 2026 menyampaikan bahwa tahapan dimulai dari pembentukan panitia pemilihan tingkat kampung oleh Badan Permusyawaratan Kampung, lalu sosialisasi regulasi dan petunjuk teknis.',
                    'Warga yang terdaftar dalam daftar pemilih tetap, termasuk yang sedang kuliah atau bekerja di luar daerah, diimbau pulang untuk memakai hak pilihnya. Artikel sumber tidak menyebut tanggal pemungutan suara; tanyakan jadwalnya ke panitia pemilihan di kampung masing-masing.'],
                'Berita Borneo', 'Mahulu Siap Gelar Piltikam Serentak 2026 di 23 Kampung',
                'https://beritaborneo.com/main/mahulu-siap-gelar-piltikam-serentak-2026-di-23-kampung/'],
            ['pengumuman', 'Beasiswa S-1 Pemkab Mahakam Ulu 2026: pilihan kampus ditambah', '2026-07-27',
                'Disdikbud Mahakam Ulu menambah pilihan perguruan tinggi untuk program beasiswa S-1 tahun 2026.',
                ['Pemerintah Kabupaten Mahakam Ulu menambah pilihan perguruan tinggi untuk program beasiswa jenjang S-1 tahun 2026. Hal ini disampaikan Kepala Dinas Pendidikan dan Kebudayaan (Disdikbud) Mahakam Ulu, Samson Batang, pada 27 Juli 2026.',
                    'Pilihan kampusnya meliputi Politeknik Negeri Samarinda, Universitas Hasanuddin, Universitas Kristen Krida Wacana, dan Sekolah Tinggi Pertanahan Nasional Yogyakarta.',
                    'Artikel sumber tidak memuat syarat maupun jadwal pendaftaran. Tanyakan keduanya langsung ke Disdikbud Mahakam Ulu.'],
                'RRI', 'Mahulu Buka Pilihan Beasiswa Beragam Kampus Tahun Anggaran 2026',
                'https://rri.co.id/sendawar/daerah-3t/2600598/mahulu-buka-pilihan-beasiswa-beragam-kampus-tahun-anggaran-2026'],
            ['kampung', 'Pemilihan Petinggi Kampung Ujoh Bilang digelar di dua TPS', '2026-05-26',
                'Pemungutan suara pemilihan Petinggi Kampung Ujoh Bilang berlangsung 25 Mei 2026 dengan 3.677 pemilih terdaftar dan lima calon.',
                ['Pemungutan suara pemilihan Petinggi Kampung Ujoh Bilang berlangsung pada 25 Mei 2026 di dua tempat pemungutan suara: TPS 01 di Lamin Adat Kampung Ujoh Bilang dan TPS 02 di Lamin Adat Aoheng.',
                    'Daftar pemilih tetap untuk kedua TPS berjumlah 3.677 orang, sekitar 1.982 di antaranya terdaftar di TPS 02. Pemilihan diikuti lima calon petinggi.',
                    'Artikel sumber tidak memuat nama calon maupun perolehan suara.'],
                'PPID Mahakam Ulu', 'Antusiasme Masyarakat Warnai Pelaksanaan Piltikam Kampung Ujoh Bilang Tahun 2026',
                'https://ppid.mahakamulukab.go.id/antusiasme-masyarakat-warnai-pelaksanaan-piltikam-kampung-ujoh-bilang-tahun-2026/'],
            ['budaya', 'Ritual Nebukoq digelar di Ujoh Bilang sebagai syukuran panen', '2026-05-29',
                'Warga Kampung Ujoh Bilang menggelar ritual adat Nebukoq pada 28 Mei sebagai syukur atas panen ladang.',
                ['Warga Kampung Ujoh Bilang menggelar ritual adat Nebukoq pada 28 Mei sebagai ungkapan syukur atas hasil panen ladang. Tokoh adat, pemerintah kampung, kelompok tani, dan warga ikut hadir.',
                    'Kepala Adat Kampung Ujoh Bilang, Amundus Lah, menjelaskan bahwa ritual ini wajib dijalankan petani setelah panen. Namanya berbeda di tiap sub-suku, tetapi maknanya sama.',
                    'Tahun ini ritual tersebut digelar secara massal untuk kedua kalinya. Kepala adat berencana menjadikannya agenda tahunan setiap 28 Mei agar generasi muda tetap mengenal tradisi ini.'],
                'RRI', 'Ritual Nebukoq Ujoh Bilang Jadi Agenda Tahunan Syukuran Panen di Mahulu',
                'https://rri.co.id/sendawar/daerah-3t/2451272/ritual-nebukoq-ujoh-bilang-jadi-agenda-tahunan-syukuran-panen-di-mahulu'],
            ['budaya', 'Festival Omin Atun Onam Adet di Long Bagun Ilir ditutup Wakil Bupati', '2026-06-11',
                'Festival adat empat hari di Kampung Long Bagun Ilir ditutup pada 9 Juni 2026. Tuan rumah 2027 adalah Kampung Long Kerioq.',
                ['Festival Omin Atun Onam Adet di Kampung Long Bagun Ilir berlangsung empat hari dan ditutup pada 9 Juni 2026 oleh Wakil Bupati Mahakam Ulu, Suhuk.',
                    'Tuan rumah tahun 2027 adalah Kampung Long Kerioq, Kecamatan Long Apari, yang ditetapkan lewat undian dalam rangkaian kegiatan adat.',
                    'Artikel sumber tidak menyebut jumlah peserta.'],
                'RRI', 'Omin Atun Onam Adet Ditutup Meriah di Mahulu',
                'https://rri.co.id/samarinda/budaya/2483576/omin-atun-onam-adet-ditutup-meriah-di-mahulu'],
            ['kesehatan', 'Bulan Anti Rabies 2026 dimulai setelah kasus positif di Ujoh Bilang', '2026-07-28',
                'Pemkab Mahakam Ulu memulai Bulan Anti Rabies pada 27 Juli 2026; 1.000 dosis tambahan disiapkan untuk Matalibaq, Long Bagun Ulu, dan Long Bagun Ilir.',
                ['Pemerintah Kabupaten Mahakam Ulu memulai Bulan Anti Rabies 2026 pada 27 Juli 2026 di Kantor Bupati. Acara dibuka Wakil Bupati Suhuk.',
                    'Gerakan ini menyusul hasil uji laboratorium per 15 Juli 2026 yang memastikan seekor anjing liar di RT 5 Kampung Ujoh Bilang positif rabies setelah menggigit empat warga.',
                    'Targetnya vaksinasi massal gratis untuk sedikitnya 400 hewan penular rabies, ditambah 1.000 dosis untuk Kampung Matalibaq, Long Bagun Ulu, dan Long Bagun Ilir. Pemilik hewan diminta memvaksin peliharaannya dan tidak membiarkannya berkeliaran. Artikel sumber tidak mencantumkan jadwal vaksinasi per kampung.'],
                'RRI', 'Cegah Rabies di Ujoh Bilang, Pemkab Mahulu Gelar Kick Off Bulan Anti Rabies 2026',
                'https://rri.co.id/samarinda/info-pemda/2604476/cegah-rabies-di-ujoh-bilang-pemkab-mahulu-gelar-kick-off-bulan-anti-rabies-2026'],
            ['kampung', 'Kampung Ujoh Bilang susun RPJMK 2026–2034 lewat musyawarah tiga hari', '2026-09-26',
                'Musyawarah penyusunan RPJMK Kampung Ujoh Bilang berlangsung 23–25 September 2026 di Kantor Petinggi.',
                ['Pemerintah Kampung Ujoh Bilang menggelar musyawarah penyusunan Rencana Pembangunan Jangka Menengah Kampung (RPJMK) 2026–2034 pada 23 sampai 25 September 2026 di Kantor Petinggi. Musyawarah dipimpin Petinggi Petrus Jumriq.',
                    'Perwakilan Badan Permusyawaratan Kampung meminta program kampung diselaraskan dengan RPJMD Kabupaten Mahakam Ulu. Perwakilan lembaga adat meminta kearifan lokal dan hak masyarakat adat tetap menjadi pertimbangan utama.',
                    'Hasilnya berupa rumusan awal dokumen RPJMK, yang selanjutnya diverifikasi dan disahkan lewat peraturan kampung.'],
                'Website Kampung Ujoh Bilang', 'Musyawarah RPJMK 2026-2034 Kampung Ujoh Bilang',
                'https://ujohbilang.mahulu.online/artikel/2026/9/26/musyawarah-rpjmk-2026-2034-kampung-ujoh-bilang'],
            ['budaya', 'Batu Majang gelar syukur panen Mecaq Undat', '2026-04-27',
                'Warga Kampung Batu Majang menggelar Syukur Panen Raya Mecaq Undat "Pebeka Kimet" pada 24 April 2026 di lamin adat.',
                ['Warga Kampung Batu Majang menggelar Syukur Panen Raya Mecaq Undat "Pebeka Kimet" Tahun Ladang 2025/2026 pada 24 April 2026 di Lamin Adat Kampung Batu Majang.',
                    'Mecaq Undat adalah acara menumbuk beras bersama sebagai ungkapan syukur atas panen padi ladang sekaligus cara menjaga adat turun-temurun. Acara dihadiri unsur pemerintah daerah, tokoh adat, dan warga.',
                    'Sambutan Bupati Angela Idang Belawan dibacakan Kepala Diskominfostandi Mahakam Ulu, Markus Wan. Pemerintah menyatakan dukungan bagi petani lewat benih, pupuk, sarana pertanian, dan pembinaan penyuluh.'],
                'PPID Mahakam Ulu', 'Batu Majang Gelar Syukur Hasil Panen, Wujud Pelestarian Budaya dan Penguatan Pertanian',
                'https://ppid.mahakamulukab.go.id/batu-majang-gelar-syukur-hasil-panen-wujud-pelestarian-budaya-dan-penguatan-pertanian/'],
            ['kampung', 'Sosialisasi Indeks Desa 2026: Long Bagun Ulu berstatus kampung maju', '2026-05-11',
                'Pemkab Mahakam Ulu menggelar sosialisasi pemutakhiran Indeks Desa 2026 pada 11 Mei 2026.',
                ['Pemerintah Kabupaten Mahakam Ulu menggelar sosialisasi pemutakhiran Indeks Desa Tahun 2026 pada 11 Mei 2026 di Kantor Sekretariat Daerah. Acara dibuka Sekretaris Daerah Stephanus Madang mewakili Bupati.',
                    'Indeks Desa dipakai untuk mengukur perkembangan dan kemandirian kampung. Data yang diinput menjadi dasar kebijakan dan penyaluran program, sehingga pemerintah kampung, operator, dan pendamping diminta menginput tepat waktu.',
                    'Dalam sosialisasi itu Kampung Long Bagun Ulu disebut memiliki skor 470 dengan status kampung maju.'],
                'RRI', 'Sosialisasi Indeks Desa 2026 Mahakam Ulu Dorong Kemandirian Kampung',
                'https://rri.co.id/samarinda/info-pemda/2406542/sosialisasi-indeks-desa-2026-mahakam-ulu-dorong-kemandirian-kampung'],
            ['kampung', 'WKRI Ujoh Bilang peringati HUT ke-102', '2026-08-02',
                'Wanita Katolik RI Paroki Santo Petrus Ujoh Bilang memperingati HUT ke-102 pada 31 Juli 2026 dengan misa syukur dan seminar.',
                ['Wanita Katolik Republik Indonesia (WKRI) Paroki Santo Petrus Ujoh Bilang memperingati HUT ke-102 pada 31 Juli 2026 di aula gereja paroki. Wakil Bupati Mahakam Ulu, Suhuk, hadir memberi sambutan.',
                    'Acara diawali misa syukur yang dipimpin Pastor Paroki RD. Agustinus Dale Weruin, lalu dilanjutkan pembinaan anggota.',
                    'Ada dua sesi berbagi: kesehatan dan perlindungan anak bersama dr. Tracy Weynata, Sp.A., serta kehidupan keluarga bersama pastor paroki.'],
                'RRI', 'WKRI Ujoh Bilang Peringati HUT Ke-102 Penuh Syukur',
                'https://rri.co.id/samarinda/budaya/2619641/wkri-ujoh-bilang-peringati-hut-ke-102-penuh-syukur'],
            ['kampung', 'Lomba HUT ke-81 RI dimulai di Alun-Alun Ujoh Bilang', '2026-08-14',
                'Rangkaian lomba HUT ke-81 RI dimulai 12 Agustus 2026; turnamen futsal diikuti 17 tim putra dan 4 tim putri.',
                ['Rangkaian lomba peringatan HUT ke-81 Republik Indonesia di Mahakam Ulu dimulai pada 12 Agustus 2026 di Lapangan Alun-Alun Ujoh Bilang.',
                    'Lombanya meliputi futsal, voli terpal, adu lari, dan permainan tradisional. Turnamen futsal berlangsung 12 sampai 15 Agustus 2026 dan diikuti 17 tim putra serta 4 tim putri.',
                    'Wakil Bupati Mahakam Ulu, Suhuk, memberi sambutan dalam pembukaan.'],
                'RRI', 'Mahakam Ulu Semarakkan HUT RI dengan Berbagai Perlombaan',
                'https://rri.co.id/samarinda/info-pemda/2650017/mahakam-ulu-semarakkan-hut-ri-dengan-berbagai-perlombaan'],
            ['kesehatan', 'Kader Pendamping Keluarga ikuti outbound di Long Melaham', '2026-08-16',
                'Outbound Kader Pendamping Keluarga Mahakam Ulu digelar 14 Agustus 2026 di Bumi Perkemahan Tanaa Mekaam Jaya, Long Melaham.',
                ['Kader Pendamping Keluarga (KPK) Mahakam Ulu mengikuti outbound pada 14 Agustus 2026 di Bumi Perkemahan Pramuka "Tanaa Mekaam Jaya", Kampung Long Melaham. Kegiatan ini bagian dari Revitalisasi KPK Mahulu 2026 dan digelar menyambut HUT ke-81 RI.',
                    'Acara diawali senam bersama, lalu lomba bakiak, sarung berantai, Rangking 1, dan yel-yel.',
                    'Perwakilan BKKBN Kalimantan Timur meminta kader memperkuat pendampingan calon pengantin, termasuk pemeriksaan kesehatan untuk mencegah anemia. Kader akan mendata dan mendampingi dari pintu ke pintu selama sekitar 7 sampai 10 hari.'],
                'RRI', 'Semarak Outbound Pererat Kebersamaan Kader KPK Mahulu',
                'https://rri.co.id/samarinda/info-pemda/2653523/semarak-outbound-pererat-kebersamaan-kader-kpk-mahulu'],
            ['kampung', 'Bulan Bakti Karang Taruna ke-66 dibuka di Lapangan Cor Ujoh Bilang', '2026-09-15',
                'Bulan Bakti Karang Taruna ke-66 dibuka 13 September 2026 dengan turnamen voli dan bulu tangkis, lomba video, dan bakti sosial.',
                ['Bulan Bakti Karang Taruna ke-66 Tahun 2026 di Mahakam Ulu dibuka pada 13 September 2026 di Lapangan Cor Ujoh Bilang. Turnamen olahraganya dibuka Asisten I Pemkab Mahakam Ulu, Agustinus Teguh Santoso, mewakili Bupati.',
                    'Rangkaiannya meliputi turnamen bola voli dan bulu tangkis, lomba video kreasi, serta bakti sosial.',
                    'Bakti sosial berupa pemeriksaan kesehatan gratis dan pembagian masker, vitamin, susu, serta sembako. Artikel sumber tidak menyebut tanggal penutupan.'],
                'RRI', 'Karang Taruna Mahulu Dorong Pemuda Aktif Berkarya',
                'https://rri.co.id/samarinda/info-pemda/2730167/karang-taruna-mahulu-dorong-pemuda-aktif-berkarya'],
            ['budaya', 'Pekan Kebudayaan Daerah Mahakam Ulu 2026 diikuti lima kecamatan', '2026-10-02',
                'Pekan Kebudayaan Daerah ke-2 dibuka 1 Oktober 2026 di Bumi Perkemahan Kabupaten Mahakam Ulu dengan sedikitnya 235 peserta.',
                ['Pekan Kebudayaan Daerah (PKD) Mahakam Ulu ke-2 dimulai pada 1 Oktober 2026 di Bumi Perkemahan Kabupaten Mahakam Ulu. Sambutan Bupati dibacakan Wakil Bupati Suhuk.',
                    'Kegiatan diikuti sedikitnya 235 peserta dan pendamping dari lima kecamatan, dengan tema "Seni Merawat Tradisi, Berkembang Dalam Karya Berakar pada Budaya".',
                    'Acaranya meliputi lomba permainan rakyat dan olahraga tradisional seperti belogo dan menyumpit, tari, sape, musik etnik, mendongeng, memasak, pasar budaya, gelar UMKM, serta seminar tentang pemanfaatan teknologi untuk budaya daerah. Artikel sumber tidak menyebut tanggal penutupan.'],
                'PPID Mahakam Ulu', 'PKD Mahulu 2026 Angkat Semangat Merawat Tradisi',
                'https://ppid.mahakamulukab.go.id/pkd-mahulu-2026-angkat-semangat-merawat-tradisi/'],
            ['budaya', 'Festival Hudoq Pekayang 2026 digelar 20–22 Oktober di Long Pahangai', '2026-10-11',
                'Festival Hudoq Pekayang 2026 dijadwalkan 20–22 Oktober 2026 di Kampung Long Pahangai II dengan 13 kampung peserta.',
                ['Festival Hudoq Pekayang 2026 dijadwalkan berlangsung 20 sampai 22 Oktober 2026 di Kampung Long Pahangai II, Kecamatan Long Pahangai, Kabupaten Mahakam Ulu.',
                    'Camat Long Pahangai, Thomas Ding, menyebut 13 kampung akan ikut serta dan panitia bersiap menyambut sekitar 3.000 tamu dan peserta.',
                    'Festival ini berlangsung di luar Kecamatan Long Bagun. Satu sumber lain mencantumkan tanggal 20–21 Oktober, jadi pastikan jadwalnya ke panitia sebelum berangkat.'],
                'Koran Jakarta', 'Lestarikan Budaya, Long Pahangai Bersiap Sambut Ribuan Tamu pada Festival Hudoq Pekayang',
                'https://koran-jakarta.com/2026-10-11/lestarikan-budaya-long-pahangai-bersiap-sambut-ribuan-tamu-pada-festival-hudoq-pekayang'],
        ];

        foreach ($rangkuman as [$kategori, $judul, $tanggal, $ringkasan, $paragraf, $media, $judulSumber, $tautan]) {
            $kat = PostCategory::firstOrCreate(['slug' => $kategori], ['name' => Str::headline($kategori), 'active' => 1]);
            $terbit = \Illuminate\Support\Carbon::parse($tanggal)->locale('id')->translatedFormat('j F Y');
            $sumber = '<p><em>Sumber: <a href="' . e($tautan) . '" target="_blank" rel="noopener">' . e($media) . ', “' . e($judulSumber) . '”</a>, terbit ' . $terbit . '. Dirangkum oleh admin situs.</em></p>';

            Post::updateOrCreate(['slug' => Str::slug($judul)], [
                'user_id' => $user->id,
                'category_id' => $kat->id,
                'title' => $judul,
                'ringkasan' => $ringkasan . ' (Sumber: ' . $media . ')',
                'content' => collect($paragraf)->map(fn ($p) => '<p>' . e($p) . '</p>')->implode('') . $sumber,
                'type' => 'post',
                'status' => 1,
                'published_at' => $tanggal . ' 08:00:00',
            ]);
        }
    }

    private function agenda(): void
    {
        // Kegiatan yang sudah diberitakan media: [mulai, selesai, nama, lokasi, uraian dengan sumbernya].
        $agenda = [
            ['2026-02-06 08:00', null, 'Musrenbang Kecamatan Long Bagun 2026', 'Balai Pertemuan Umum Kampung Ujoh Bilang',
                'Hasilnya menjadi bahan penyusunan RKPD Mahakam Ulu 2027. Sumber: Media Kaltim, 7 Februari 2026.'],
            ['2026-05-25 08:00', null, 'Pemungutan suara pemilihan Petinggi Kampung Ujoh Bilang', 'Lamin Adat Kampung Ujoh Bilang dan Lamin Adat Aoheng',
                'Dua TPS, 3.677 pemilih terdaftar, lima calon. Sumber: PPID Mahakam Ulu, 26 Mei 2026.'],
            ['2026-05-28 08:00', null, 'Ritual adat Nebukoq (syukuran panen)', 'Kampung Ujoh Bilang',
                'Kepala adat berencana menggelarnya setiap 28 Mei. Sumber: RRI, 29 Mei 2026.'],
            ['2026-06-06 08:00', '2026-06-09 17:00', 'Festival Omin Atun Onam Adet', 'Kampung Long Bagun Ilir',
                'Berlangsung empat hari, ditutup Wakil Bupati Suhuk pada 9 Juni 2026. Sumber: RRI, 11 Juni 2026.'],
            ['2026-07-27 08:00', null, 'Kick off Bulan Anti Rabies 2026', 'Kantor Bupati Mahakam Ulu, Ujoh Bilang',
                'Vaksinasi massal gratis hewan penular rabies; 1.000 dosis tambahan untuk Matalibaq, Long Bagun Ulu, dan Long Bagun Ilir. Sumber: RRI, 28 Juli 2026.'],
            ['2026-08-04 08:00', null, 'Serah terima jabatan Petinggi Kampung Ujoh Bilang', 'Balai adat Kampung Ujoh Bilang',
                'Petrus Jumriq menggantikan penjabat petinggi Romensius Kuleh. Sumber: RRI, 4 Agustus 2026.'],
            ['2026-08-17 08:00', null, 'Peresmian playground anak Ujoh Bilang', 'Lapangan Cor Ujoh Bilang',
                'Diresmikan Bupati Angela Idang Belawan usai upacara HUT ke-81 RI. Sumber: RRI, 18 Agustus 2026.'],
            ['2026-09-23 08:00', '2026-09-25 17:00', 'Musyawarah RPJMK 2026–2034 Kampung Ujoh Bilang', 'Kantor Petinggi Kampung Ujoh Bilang',
                'Dipimpin Petinggi Petrus Jumriq. Sumber: Website Kampung Ujoh Bilang, 26 September 2026.'],
            ['2026-09-24 08:00', '2026-09-25 17:00', 'Presentasi laporan antara kajian Dermaga Batu Dinding', 'Kantor Bupati Mahakam Ulu, Ujoh Bilang',
                'Membahas rencana induk, DLKr, DLKp, dan Andalalin dermaga di Long Melaham. Sumber: Media Kaltim, 25 September 2026.'],
            ['2026-04-24 08:00', null, 'Syukur Panen Raya Mecaq Undat "Pebeka Kimet"', 'Lamin Adat Kampung Batu Majang',
                'Acara menumbuk beras bersama sebagai syukur panen padi ladang 2025/2026. Sumber: PPID Mahakam Ulu, 27 April 2026.'],
            ['2026-05-11 08:00', null, 'Sosialisasi Indeks Desa 2026', 'Kantor Sekretariat Daerah Mahakam Ulu, Ujoh Bilang',
                'Dibuka Sekretaris Daerah Stephanus Madang; Long Bagun Ulu disebut berstatus kampung maju. Sumber: RRI, 11 Mei 2026.'],
            ['2026-07-31 08:00', null, 'Peringatan HUT ke-102 WKRI Ujoh Bilang', 'Aula Gereja Paroki Santo Petrus Ujoh Bilang',
                'Misa syukur, seminar kesehatan anak, dan sesi keluarga. Sumber: RRI, 2 Agustus 2026.'],
            ['2026-08-12 08:00', '2026-08-15 17:00', 'Lomba HUT ke-81 RI dan turnamen futsal', 'Lapangan Alun-Alun Ujoh Bilang',
                'Futsal (17 tim putra, 4 tim putri), voli terpal, adu lari, dan permainan tradisional. Sumber: RRI, 14 Agustus 2026.'],
            ['2026-08-14 08:00', null, 'Outbound Kader Pendamping Keluarga Mahakam Ulu', 'Bumi Perkemahan Tanaa Mekaam Jaya, Kampung Long Melaham',
                'Bagian dari Revitalisasi KPK Mahulu 2026, menyambut HUT ke-81 RI. Sumber: RRI, 16 Agustus 2026.'],
            ['2026-09-13 08:00', null, 'Pembukaan Bulan Bakti Karang Taruna ke-66', 'Lapangan Cor Ujoh Bilang',
                'Turnamen voli dan bulu tangkis, lomba video kreasi, dan bakti sosial. Sumber: RRI, 15 September 2026.'],
            ['2026-10-01 08:00', null, 'Pekan Kebudayaan Daerah Mahakam Ulu ke-2', 'Bumi Perkemahan Kabupaten Mahakam Ulu',
                'Diikuti sedikitnya 235 peserta dari lima kecamatan. Sumber: PPID Mahakam Ulu, 2 Oktober 2026.'],
            ['2026-10-20 08:00', '2026-10-22 17:00', 'Festival Hudoq Pekayang 2026', 'Kampung Long Pahangai II, Kecamatan Long Pahangai',
                'Di luar Kecamatan Long Bagun; 13 kampung peserta. Satu sumber lain menyebut 20–21 Oktober, pastikan ke panitia. Sumber: Koran Jakarta, 11 Oktober 2026.'],
        ];

        foreach ($agenda as [$mulai, $selesai, $nama, $lokasi, $uraian]) {
            Agenda::updateOrCreate(['caption' => $nama, 'schedule' => $mulai], [
                'end_at' => $selesai,
                'location' => $lokasi,
                'description' => $uraian,
            ]);
        }
    }

    private function dokumen(): void
    {
        // Daftar informasi berkala (UU 14/2008 Pasal 9): profil, kegiatan dan kinerja, serta laporan keuangan.
        // Berkasnya diunggah admin; sebelum itu situs menampilkan "Berkas belum diunggah".
        $berkala = [
            ['profil', null, 'Profil Kecamatan Long Bagun'],
            ['profil', null, 'Struktur organisasi dan daftar pejabat Kantor Camat Long Bagun'],
            ['profil', null, 'Tugas pokok dan fungsi Kantor Camat Long Bagun'],
            ['profil', 2025, 'Laporan Harta Kekayaan Penyelenggara Negara (LHKPN) pejabat wajib lapor Tahun 2025'],
            ['perencanaan', null, 'Rencana Strategis (Renstra) Kecamatan Long Bagun'],
            ['perencanaan', null, 'Indikator Kinerja Utama (IKU) Kecamatan Long Bagun'],
            ['perencanaan', 2026, 'Rencana Kerja (Renja) Tahun 2026'],
            ['perencanaan', 2026, 'Perjanjian Kinerja Tahun 2026'],
            ['perencanaan', 2026, 'Rencana Umum Pengadaan (RUP) Tahun 2026'],
            ['anggaran', 2026, 'Rencana Kerja dan Anggaran (RKA) Tahun 2026'],
            ['anggaran', 2026, 'Dokumen Pelaksanaan Anggaran (DPA) Tahun 2026'],
            ['anggaran', 2025, 'Laporan Realisasi Anggaran Tahun 2025'],
            ['anggaran', 2025, 'Laporan Keuangan Tahun 2025'],
            ['anggaran', 2025, 'Daftar aset dan inventaris Tahun 2025'],
            ['laporan_kinerja', 2025, 'Laporan Kinerja Instansi Pemerintah (LKjIP) Tahun 2025'],
            ['laporan_kinerja', 2025, 'Hasil Survei Kepuasan Masyarakat (SKM) Tahun 2025'],
            ['layanan_informasi', 2025, 'Laporan layanan informasi publik Tahun 2025'],
        ];

        // Informasi serta merta (UU 14/2008 Pasal 10): yang dapat mengancam hajat hidup orang banyak dan ketertiban umum.
        $sertaMerta = [
            ['darurat', null, 'Peringatan dini banjir dan tinggi muka air Sungai Mahakam'],
            ['darurat', null, 'Informasi bencana alam: banjir, tanah longsor, serta kebakaran hutan dan lahan'],
            ['darurat', null, 'Informasi kabut asap dan imbauan kesehatan'],
            ['darurat', null, 'Informasi wabah dan kejadian luar biasa penyakit, termasuk rabies'],
            ['darurat', null, 'Informasi keselamatan pelayaran Sungai Mahakam saat banjir dan kemarau'],
            ['darurat', null, 'Prosedur evakuasi dan lokasi pengungsian'],
            ['darurat', null, 'Nomor telepon darurat dan pos siaga'],
            ['darurat', null, 'Gangguan layanan dan penutupan sementara Kantor Camat'],
        ];

        // Informasi tersedia setiap saat (UU 14/2008 Pasal 11).
        $setiapSaat = [
            ['layanan_informasi', null, 'Daftar Informasi Publik (DIP) Kantor Camat Long Bagun'],
            ['layanan_informasi', null, 'Tata cara permohonan informasi dan pengajuan keberatan'],
            ['layanan_informasi', null, 'Laporan pelayanan akses informasi publik'],
            ['regulasi', null, 'Keputusan Camat Long Bagun beserta pertimbangannya'],
            ['regulasi', null, 'Kebijakan Kantor Camat Long Bagun beserta dokumen pendukungnya'],
            ['regulasi', null, 'Informasi dan kebijakan yang disampaikan pejabat dalam pertemuan terbuka untuk umum'],
            ['perencanaan', null, 'Rencana kerja kegiatan dan perkiraan pengeluaran tahunan'],
            ['kerja_sama', null, 'Perjanjian dan kerja sama Kantor Camat dengan pihak ketiga'],
            ['sop', null, 'Standar Operasional Prosedur (SOP) pelayanan Kantor Camat'],
            ['sop', null, 'Standar pelayanan dan maklumat pelayanan'],
            ['profil', null, 'Data dan statistik Kecamatan Long Bagun'],
        ];

        foreach (['berkala' => $berkala, 'serta_merta' => $sertaMerta, 'setiap_saat' => $setiapSaat] as $kategori => $daftar) {
            foreach ($daftar as [$jenis, $tahun, $judul]) {
                DokumenPublik::firstOrCreate(['judul' => $judul], [
                    'jenis' => $jenis,
                    'kategori' => $kategori,
                    'tahun' => $tahun,
                    'status' => true,
                ]);
            }
        }
    }
}
