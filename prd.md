# PRD Website Kecamatan Long Bagun

Oct 8, 2026 · @kaminari

Dokumen ini menetapkan apa yang harus dibangun untuk website resmi Kecamatan Long Bagun, Kabupaten Mahakam Ulu, dan memuat seluruh data awal sehingga bisa disalin utuh ke dalam prompt AI pembangun situs.

## 1. Ringkasan produk

Website ini adalah kanal informasi dan layanan resmi Kantor Camat Long Bagun, yang juga memperkenalkan 11 kampung di wilayahnya.

**Tujuan**

1. Warga mengetahui syarat, alur, dan jam layanan kecamatan tanpa harus datang ke kantor lebih dulu.
2. Data dasar kecamatan dan tiap kampung tersedia di satu tempat, dengan sumber dan tahun yang jelas.
3. Berita, pengumuman, dan dokumen publik (PPID) terbit teratur dan mudah dicari.
4. Potensi wisata dan budaya Long Bagun dikenal pengunjung dari luar daerah.

**Pengguna sasaran**

| Pengguna | Kebutuhan utama | Perangkat |
| --- | --- | --- |
| Warga 11 kampung | Syarat layanan, formulir, pengumuman, kontak kantor | Ponsel, sinyal terbatas |
| Aparatur kampung dan kecamatan | Data, dokumen perencanaan, agenda | Ponsel dan laptop |
| Peneliti, wartawan, instansi lain | Data kependudukan, profil wilayah, dokumen publik | Laptop |
| Wisatawan dan tamu | Destinasi, acara adat, cara menuju Long Bagun | Ponsel |
| Admin kantor camat | Menulis berita dan memperbarui data tanpa bantuan programmer | Laptop |

**Ukuran keberhasilan**

- Syarat tiap layanan bisa ditemukan dalam dua klik dari beranda.
- Beranda termuat di bawah 3 detik pada jaringan 3G.
- Admin bisa menerbitkan berita baru dalam waktu kurang dari 10 menit.
- Semua 11 halaman kampung terisi lengkap saat peluncuran.

## 2. Ruang lingkup

Versi pertama adalah situs informasi dengan panel admin; layanan tidak diproses secara daring.

| Masuk versi pertama | Tidak masuk versi pertama |
| --- | --- |
| Beranda, Profil, 11 halaman Kampung, Layanan, Data, Potensi dan wisata, Informasi, PPID, Kontak | Pengajuan layanan daring dengan unggah berkas dan pelacakan status |
| Panel admin untuk berita, pengumuman, agenda, dokumen, data kampung, pegawai | Akun warga dan login pengguna umum |
| Formulir pengaduan yang mengirim pesan ke email kantor | Integrasi dengan sistem Dukcapil atau aplikasi kabupaten |
| Pencarian di seluruh situs | Versi bahasa Inggris |
| Pita peringatan darurat yang dinyalakan admin | Aplikasi ponsel |
| Tombol WhatsApp ke nomor kantor | Pembayaran daring |

## 3. Struktur situs

Situs terdiri dari sembilan bagian menu dan 30-an halaman tetap, semuanya paling jauh dua klik dari beranda.

| Menu | Halaman | URL |
| --- | --- | --- |
| Beranda | Beranda | `/` |
| Profil | Sejarah | `/profil/sejarah` |
| Profil | Visi dan misi | `/profil/visi-misi` |
| Profil | Geografi dan peta | `/profil/geografi` |
| Profil | Struktur organisasi | `/profil/struktur-organisasi` |
| Kampung | Daftar kampung | `/kampung` |
| Kampung | Profil kampung (11 halaman) | `/kampung/{slug}` |
| Layanan | Daftar layanan | `/layanan` |
| Layanan | Detail layanan | `/layanan/{slug}` |
| Layanan | Unduh formulir | `/layanan/formulir` |
| Layanan | Pengaduan | `/layanan/pengaduan` |
| Data | Ringkasan data | `/data` |
| Data | Kependudukan, Pendidikan, Kesehatan, Ekonomi | `/data/{topik}` |
| Potensi dan wisata | Daftar destinasi dan acara adat | `/wisata` |
| Potensi dan wisata | Detail destinasi | `/wisata/{slug}` |
| Potensi dan wisata | Cara menuju Long Bagun | `/wisata/akses` |
| Informasi | Berita | `/berita` dan `/berita/{slug}` |
| Informasi | Pengumuman | `/pengumuman` dan `/pengumuman/{slug}` |
| Informasi | Agenda | `/agenda` |
| Informasi | Galeri | `/galeri` |
| PPID | Dokumen publik | `/ppid` |
| Kontak | Kontak dan peta lokasi | `/kontak` |
| (admin) | Panel admin | `/admin` |

Slug kampung: `ujoh-bilang`, `long-melaham`, `long-bagun-ulu`, `mamahak-ilir`, `batu-majang`, `long-bagun-ilir`, `batoq-kelo`, `rukun-damai`, `long-merah`, `long-hurai`, `mamahak-ulu`.

## 4. Kebutuhan fungsional

Ada 24 kebutuhan; yang bertanda P1 wajib ada saat peluncuran, P2 boleh menyusul.

| ID | Bagian | Kebutuhan | Prioritas |
| --- | --- | --- | --- |
| F-01 | Semua halaman | Bilah atas berisi jam pelayanan, nomor telepon/WhatsApp, dan email kantor | P1 |
| F-02 | Semua halaman | Kepala halaman berisi lambang kabupaten, nama "Kecamatan Long Bagun", menu utama, dan kotak pencarian; di ponsel menu menjadi tombol garis tiga | P1 |
| F-03 | Semua halaman | Kaki halaman berisi alamat kantor, peta lokasi, tautan ke situs Pemkab Mahakam Ulu, PPID, SP4N-LAPOR, dan BPS Mahakam Ulu | P1 |
| F-04 | Semua halaman | Pita peringatan darurat di paling atas, tampil hanya bila admin menyalakannya, dengan teks dan tingkat (info, waspada, bahaya) | P1 |
| F-05 | Semua halaman | Tombol WhatsApp mengambang yang membuka obrolan ke nomor kantor | P1 |
| F-06 | Semua halaman | Pencarian di seluruh berita, pengumuman, layanan, kampung, dan dokumen | P2 |
| F-07 | Beranda | Sembilan blok berurutan: bilah atas, kepala halaman, gambar utama dengan dua tombol, enam kartu layanan cepat, empat angka kunci, berita dan pengumuman, kisi 11 kampung, tiga destinasi, kaki halaman | P1 |
| F-08 | Beranda | Angka kunci menampilkan jumlah kampung, penduduk, luas wilayah, dan jumlah RT beserta tahun dan sumber data | P1 |
| F-09 | Profil | Halaman teks untuk sejarah, visi dan misi, serta geografi dengan peta wilayah | P1 |
| F-10 | Profil | Struktur organisasi menampilkan foto, nama, dan jabatan tiap pegawai dari koleksi Pegawai | P1 |
| F-11 | Kampung | Daftar 11 kampung sebagai kartu, bisa diurutkan menurut nama atau jumlah penduduk | P1 |
| F-12 | Kampung | Halaman kampung berisi foto, kotak fakta (petinggi, luas, penduduk, kepadatan, RT, kode wilayah), uraian, potensi, galeri, kontak, dan peta | P1 |
| F-13 | Layanan | Daftar layanan dengan penyaring kategori | P1 |
| F-14 | Layanan | Halaman layanan berisi syarat sebagai daftar centang, alur bernomor, waktu penyelesaian, biaya, tombol unduh formulir, dan kontak petugas | P1 |
| F-15 | Layanan | Formulir pengaduan (nama, kampung, nomor kontak, isi aduan) yang mengirim pesan ke email kantor dan menampilkan tanda terima; dilindungi dari spam | P1 |
| F-16 | Data | Halaman data menampilkan tabel dan grafik batang sederhana untuk penduduk per kampung, penduduk per tahun, dan agama, dengan sumber dan tahun | P1 |
| F-17 | Data | Tombol unduh tiap tabel sebagai CSV dan tautan ke publikasi BPS | P2 |
| F-18 | Potensi dan wisata | Kartu destinasi dan acara adat berfoto, dengan kategori, kampung, dan uraian | P1 |
| F-19 | Potensi dan wisata | Halaman "Cara menuju Long Bagun" menjelaskan jalur sungai, darat, dan udara beserta tanggal pembaruan terakhir | P1 |
| F-20 | Informasi | Berita dan pengumuman tampil terbaru lebih dulu, dengan kategori, tanggal, foto, tombol bagikan ke WhatsApp dan Facebook, serta halaman bernomor | P1 |
| F-21 | Informasi | Agenda sebagai daftar bertanggal; acara lampau otomatis pindah ke arsip | P2 |
| F-22 | Informasi | Galeri foto berkelompok per kegiatan, dengan keterangan dan kredit foto | P2 |
| F-23 | PPID | Daftar dokumen dengan penyaring jenis (perencanaan, laporan kinerja, anggaran, regulasi, SOP) dan tahun; tiap dokumen bisa diunduh sebagai PDF | P1 |
| F-24 | Admin | Login admin; tambah, ubah, hapus, dan simpan sebagai draf untuk semua koleksi di bagian 5; unggah foto dengan pengecilan otomatis; dua peran: admin dan penulis | P1 |

## 5. Model konten

Sepuluh koleksi berikut dikelola dari panel admin; tidak ada isi yang ditulis mati di dalam kode.

| Koleksi | Kolom |
| --- | --- |
| Pengaturan situs | nama situs, alamat kantor, telepon, WhatsApp, email, jam pelayanan, koordinat kantor, tautan media sosial, peringatan darurat (aktif, teks, tingkat) |
| Kampung | nama, slug, kode wilayah, luas km², penduduk, tahun data, jumlah RT, nama petinggi, foto petinggi, uraian, potensi, foto utama, galeri, alamat kantor kampung, kontak, koordinat |
| Pegawai | nama, jabatan, urutan tampil, foto, atasan |
| Layanan | nama, slug, kategori, ringkasan, syarat (daftar), alur (daftar berurutan), waktu penyelesaian, biaya, berkas formulir, kontak petugas |
| Berita | judul, slug, tanggal terbit, kategori, foto utama, kredit foto, ringkasan, isi, penulis, status (draf atau terbit) |
| Pengumuman | judul, slug, tanggal, isi, lampiran, tanggal berakhir |
| Agenda | judul, tanggal mulai, tanggal selesai, lokasi, uraian |
| Destinasi | nama, slug, jenis (alam, budaya, acara adat), kampung, uraian, cara mencapai, waktu terbaik, foto utama, galeri, kredit foto |
| Dokumen PPID | judul, jenis, tahun, berkas PDF, tanggal unggah |
| Statistik | topik, label, nilai, satuan, tahun, sumber, tautan sumber |

Setiap foto menyimpan teks alternatif dan kredit. Setiap angka di koleksi Statistik dan Kampung menyimpan tahun dan sumbernya.

## 6. Data awal

Semua data di bagian ini dimasukkan sebagai isi awal situs. Angka bersumber dari BPS melalui Wikipedia dan wajib dicocokkan ke publikasi BPS sebelum peluncuran; butir yang bertanda "cek" punya angka pembanding yang berbeda.

### 6.1 Identitas kecamatan

| Butir | Nilai |
| --- | --- |
| Nama | Kecamatan Long Bagun |
| Kabupaten, provinsi | Mahakam Ulu, Kalimantan Timur |
| Ibu kota kecamatan | Kampung Ujoh Bilang (juga ibu kota kabupaten) |
| Kode wilayah | 64.11.01 |
| Kode pos | 75767 |
| Luas wilayah | 4.801,22 km² (cek: sumber lain 4.971,2 km²) |
| Jumlah kampung | 11 |
| Jumlah RT | 58 (cek: penjumlahan per kampung 57) |
| Titik koordinat | 0°32′58,56″ LU, 115°15′26,89″ BT (desimal 0,5496; 115,2575) |
| Bentang wilayah | 114°53′35″ sampai 115°39′08″ BT; 0°21′ sampai 1°23′ LU |
| Zona waktu | WITA (UTC+8) |
| Letak | Bagian hulu Sungai Mahakam |
| Pembentukan kabupaten | UU 2/2013, pemekaran dari Kutai Barat |
| Sebutan kepala kampung | Petinggi |

### 6.2 Sebelas kampung

| Kampung | Slug | Kode wilayah | Luas (km²) | Penduduk 2024 | Kepadatan (jiwa/km²) | RT |
| --- | --- | --- | --- | --- | --- | --- |
| Ujoh Bilang | ujoh-bilang | 64.11.01.2006 | 354,73 | 5.474 | 15,4 | 15 |
| Long Melaham | long-melaham | 64.11.01.2002 | 102,44 | 2.198 | 21,5 | 5 |
| Long Bagun Ulu | long-bagun-ulu | 64.11.01.2008 | 567,04 | 1.693 | 3,0 | 6 |
| Mamahak Ilir | mamahak-ilir | 64.11.01.2003 | 638,81 | 1.541 | 2,4 | 5 |
| Batu Majang | batu-majang | 64.11.01.2005 | 135,54 | 1.215 | 9,0 | 7 |
| Long Bagun Ilir | long-bagun-ilir | 64.11.01.2007 | 74,63 | 1.160 | 15,5 | 4 |
| Batoq Kelo | batoq-kelo | 64.11.01.2009 | 1.530,76 | 1.046 | 0,7 | 4 |
| Rukun Damai | rukun-damai | 64.11.01.2011 | 12,30 | 993 | 80,7 | 5 |
| Long Merah | long-merah | 64.11.01.2010 | 311,04 | 540 | 1,7 | 2 |
| Long Hurai | long-hurai | 64.11.01.2001 | 41,18 | 437 | 10,6 | 2 |
| Mamahak Ulu | mamahak-ulu | 64.11.01.2004 | 996,28 | 360 | 0,4 | 2 |

Catatan kampung: Ujoh Bilang adalah ibu kota kecamatan dan kabupaten. Batu Dinding berada di Long Melaham. Batu Majang adalah permukiman Dayak Kenyah di pertemuan Sungai Alan dan Sungai Mahakam. Long Bagun Ilir menjadi tuan rumah Festival Omin Atun Onam Adet 2026. Ejaan lain yang beredar: Memahak Ilir atau Mamahak Besar untuk Mamahak Ilir, dan Batoq Kelau untuk Batoq Kelo.

### 6.3 Penduduk dan agama

| Tahun | Penduduk (jiwa) | Keterangan |
| --- | --- | --- |
| 2025 | 17.017 | Perkiraan pertengahan tahun |
| 2024 | 16.744 | Kepadatan sekitar 3,5 jiwa/km² |
| 2020 | 14.462 | Sensus Penduduk 2020 |
| 2010 | 7.886 | Sensus Penduduk 2010 |

| Agama (2022) | Jiwa | Persen |
| --- | --- | --- |
| Katolik | 8.899 | 56,7 |
| Islam | 4.483 | 28,5 |
| Protestan | 2.311 | 14,7 |
| Hindu | 8 | 0,05 |
| Buddha | 2 | 0,01 |

Penduduk umumnya suku Dayak (antara lain Bahau, Kenyah, Aoheng, Seputan, dan Bukot) bersama pendatang dari berbagai daerah. Jangan tampilkan rincian laki-laki dan perempuan; angkanya belum terverifikasi.

### 6.4 Pemerintahan

| Jabatan | Nama | Status |
| --- | --- | --- |
| Camat Long Bagun | BONIFASIUS, S.Hut | Dari sumber awal 2025; wajib dikonfirmasi |
| Bupati Mahakam Ulu | Angela Idang Belawan | Situs Prokopim Mahakam Ulu |
| Wakil Bupati Mahakam Ulu | Suhuk, S.E. | Situs Prokopim Mahakam Ulu |

### 6.5 Akses menuju Long Bagun

| Jalur | Isi halaman |
| --- | --- |
| Sungai | Kapal penumpang dari Samarinda dan speedboat dari Tering (Kutai Barat). Saat air dangkal, kapal dari Samarinda hanya sampai Long Iram. |
| Darat | Ruas Tering–Long Bagun sekitar 143 km. Pemprov Kaltim menargetkan sisa 7,65 km dari 31,53 km yang ditanganinya tuntas pada 2026. Samarinda–Ujoh Bilang 14 sampai 15 jam menurut catatan 2021. |
| Udara | Bandara Ujoh Bilang dalam pembangunan, landasan pacu 750 m x 23 m, target operasi 2026 atau setelahnya. Status terkini wajib dikonfirmasi. |

Kecamatan ini rawan banjir luapan Sungai Mahakam; pos pantau tinggi muka air berada di Jembatan Pelabuhan Ujoh Bilang.

### 6.6 Wisata dan budaya

| Nama | Slug | Jenis | Lokasi | Uraian |
| --- | --- | --- | --- | --- |
| Batu Dinding | batu-dinding | Alam | Kampung Long Melaham | Tebing batu di tepi Sungai Mahakam, diberitakan setinggi 100 m dan sepanjang 800 m |
| Kampung Batu Majang | kampung-batu-majang | Budaya | Kampung Batu Majang | Permukiman Dayak Kenyah di pertemuan Sungai Alan dan Sungai Mahakam; namanya dari batu besar yang terpotong aliran Sungai Alan |
| Riam Udang | riam-udang | Alam | Sungai Mahakam, arah hulu | Jeram yang dilewati perjalanan menuju Long Pahangai dan Long Apari; letak administratifnya perlu dipastikan |
| Festival Omin Atun Onam Adet | omin-atun-onam-adet | Acara adat | Long Bagun Ilir (2026) | Digelar 6 sampai 9 Juni 2026, diikuti 10 kampung sub-suku Aoheng, Seputan, dan Bukot |
| Hudoq Pekayang | hudoq-pekayang | Acara adat | Tingkat kabupaten | Upacara tari topeng musim tanam padi, sekitar September sampai Oktober |
| Hutan dataran rendah Long Bagun | hutan-long-bagun | Alam | Hulu Mahakam | Blok hutan sekitar 350.000 ha, tercatat sebagai Daerah Penting bagi Burung |

### 6.7 Enam layanan cepat di beranda

Pengantar KTP dan KK, Surat pindah, Legalisasi dokumen, Rekomendasi izin usaha, Unduh formulir, dan Pengaduan warga. Ini daftar umum layanan kecamatan; syarat dan alurnya harus diisi dari SOP kantor camat.

### 6.8 Berita awal

Satu berita nyata tersedia sebagai isi awal: "Festival Omin Atun Onam Adet digelar di Kampung Long Bagun Ilir", 9 Juni 2026, kategori Budaya. Festival dibuka Wakil Bupati Suhuk pada 6 Juni 2026 dan ditutup 9 Juni 2026; tuan rumah 2027 adalah Kampung Long Kerioq, Kecamatan Long Apari.

## 7. Aset

Hanya lambang kabupaten yang saya temukan sebagai berkas resmi siap pakai. Foto camat, foto kantor camat terkini, dan foto pimpinan kabupaten tidak tersedia di sumber terbuka dan harus dipasok oleh kantor camat.

### 7.1 Aset yang ditemukan

| Aset | Alamat berkas | Pemilik | Boleh dipakai? |
| --- | --- | --- | --- |
| Lambang Kabupaten Mahakam Ulu (PNG) | `https://mahakamulukab.go.id/portal/assets/img/logo-pemkab-mahulu.png` | [Pemkab Mahakam Ulu](https://mahakamulukab.go.id/) | Ya, untuk situs perangkat daerahnya sendiri; unduh dan simpan di situs, jangan tautkan langsung |
| Lambang Kabupaten, versi persegi 270 px | `https://prokopim.mahakamulukab.go.id/wp-content/uploads/2024/07/cropped-Logo-Pemkab-512pix-270x270.png` | [Prokopim Mahakam Ulu](https://prokopim.mahakamulukab.go.id/) | Ya, cocok untuk ikon tab peramban |
| Foto tebing Batu Dinding di Long Melaham | Gambar utama di [Wikipedia: Long Bagun](https://en.wikipedia.org/wiki/Long_Bagun) | Wikimedia Commons | Kemungkinan ya dengan mencantumkan kredit; buka halaman berkasnya untuk melihat lisensi dan nama pemotret |
| Foto Musrenbang Kecamatan Long Bagun, 6 Februari 2026 | Empat foto di [MediaKaltim](https://mediakaltim.com/musrenbang-long-bagun-jadi-fondasi-penyusunan-rkpd-mahulu-2027/) | MediaKaltim, kredit "Istimewa" | Hanya dengan izin; kemungkinan aslinya dari humas pemkab, jadi minta berkas asli ke sana |
| Foto renovasi kantor camat, Agustus 2017 | [EksposKaltim](https://eksposkaltim.com/berita-5594-kantor-camat-long-bagun-rampung-akhir-tahun-.html) | Ekspos/Arsyad | Tidak; foto lama saat pembongkaran dan berhak cipta |

Saya tidak bisa mengunduh atau membuka berkas gambar dari lingkungan kerja ini, sehingga isi dan ukuran tiap gambar belum saya lihat sendiri. Berita 2017 itu menyebut kantor camat berada di Jalan Poros Ujoh Bilang–Long Bagun; konfirmasikan sebagai alamat.

### 7.2 Aset yang harus dipasok kantor camat

AI pembangun menyiapkan tempat untuk berkas berikut di folder `/public/images/` dan menampilkan kotak pengganti berlabel sampai berkasnya ada.

| Berkas | Isi | Ukuran minimum |
| --- | --- | --- |
| `lambang-mahulu.png` | Lambang kabupaten, latar transparan | 512 x 512 px |
| `hero.jpg` | Foto lebar Sungai Mahakam atau Batu Dinding | 1920 x 1080 px |
| `kantor-camat.jpg` | Tampak depan kantor camat | 1600 x 900 px |
| `pimpinan/camat.jpg` | Foto resmi camat | 800 x 1000 px |
| `pimpinan/sekcam.jpg` dan pegawai lain | Foto resmi sekretaris camat dan para kepala seksi | 800 x 1000 px |
| `pimpinan/bupati.jpg`, `pimpinan/wakil-bupati.jpg` | Foto resmi dari Prokopim Mahakam Ulu | 800 x 1000 px |
| `kampung/{slug}.jpg` | Satu foto utama untuk tiap 11 kampung | 1600 x 900 px |
| `petinggi/{slug}.jpg` | Foto petinggi tiap kampung | 800 x 1000 px |
| `wisata/{slug}.jpg` | Satu foto utama tiap destinasi di bagian 6.6 | 1600 x 900 px |
| `peta-kecamatan.png` | Peta wilayah kecamatan dengan batas kampung | 1600 px lebar |

Semua foto disimpan dalam format WebP atau JPEG terkompresi, masing-masing di bawah 200 KB setelah diolah, dengan teks alternatif dan kredit pemotret.

## 8. Desain

Tampilan dominan putih, lembut, dan minimalis, dengan gradasi hijau emerald muda ke putih. Hijau tua dan emas hanya menjadi aksen. Token warna dan komponen ada di `public/css/site.css`.

| Peran | Nilai |
| --- | --- |
| Latar halaman | `#FFFFFF`; blok berselang `#F8FAF9` |
| Gradasi emerald (kepala halaman, blok kampung, kartu layanan utama, kaki halaman) | `#A7F3D0`, `#D1FAE5`, `#ECFDF5` memudar ke `#FFFFFF` |
| Teks utama | `#16211E` |
| Teks sekunder | `#5C6965` |
| Garis dan batas kartu | `#E7ECEA` |
| Aksen hijau (menu aktif, tautan, ikon) | `#0F5A49`; emerald `#10B981` untuk penanda |
| Aksen emas (garis judul, lencana kategori) | `#D9A441` |
| Tombol utama | Latar `#16211E` dengan teks putih; tombol kedua putih bergaris tepi |
| Huruf | Montserrat (judul, tebal 600 sampai 800) dan Inter (isi, tebal 400 sampai 700) |
| Ukuran teks | Isi 15 sampai 16 px; judul bagian 32 px; judul gambar utama 58 px di desktop dan 34 px di ponsel |
| Sudut | Kartu 24 px; tombol 14 px; menu dan lencana berbentuk kapsul |
| Lebar isi | Maksimum 1120 px, jarak tepi 24 px |
| Ikon | Garis (stroke) 1,75 px, satu gaya; tanpa emoji |
| Corak | Pita meliuk dengan titik dan tetesan bergaya ornamen Dayak, di blok kampung, kartu layanan utama, kepala halaman dalam, dan kaki halaman |
| Peta | Batas 11 kampung dari BIG (delineasi batas desa 2018, indikatif) dan alur Sungai Mahakam |

Aturan tata letak: kisi kartu memakai kolom otomatis yang menumpuk menjadi satu kolom di ponsel. Tidak ada slider gambar atau video putar otomatis. Gradasi hanya memakai warna emerald muda ke putih; gambar utama beranda memakai foto Sungai Mahakam yang memudar ke gradasi itu. Kontras teks minimal 4,5:1.

## 9. Kebutuhan non-fungsional

Batasan terpenting adalah jaringan: sebagian besar pengunjung membuka situs dari ponsel dengan sinyal terbatas di hulu Mahakam.

| ID | Bidang | Kebutuhan |
| --- | --- | --- |
| N-01 | Kinerja | Berat beranda di bawah 1 MB; termuat di bawah 3 detik pada 3G; gambar dimuat malas (lazy) dan disajikan dalam ukuran sesuai layar |
| N-02 | Ponsel | Semua halaman berfungsi pada lebar 360 px tanpa gulir mendatar; sasaran sentuh minimal 44 px |
| N-03 | Aksesibilitas | Memenuhi WCAG 2.1 tingkat AA: teks alternatif, navigasi papan ketik, label formulir, urutan judul yang benar |
| N-04 | Bahasa | Seluruh antarmuka berbahasa Indonesia; format angka 16.744 dan 4.801,22; format tanggal "9 Juni 2026" |
| N-05 | SEO | Judul dan deskripsi unik tiap halaman, peta situs XML, data terstruktur untuk instansi pemerintah dan artikel berita, gambar pratinjau saat dibagikan |
| N-06 | Keamanan | HTTPS; kata sandi admin disimpan sebagai hash; pembatasan percobaan login; validasi dan penyaringan semua masukan formulir; unggahan dibatasi pada JPG, PNG, WebP, dan PDF |
| N-07 | Privasi | Formulir pengaduan hanya meminta nama, kampung, nomor kontak, dan isi aduan; data tidak ditampilkan ke publik |
| N-08 | Pengelolaan | Admin non-teknis dapat memperbarui semua isi; ada cadangan data harian |
| N-09 | Teknologi | Tidak mengikat kerangka kerja tertentu; hasil harus bisa dipasang di hosting milik Diskominfo Mahakam Ulu atau hosting umum |
| N-10 | Peramban | Chrome, Safari, dan Firefox dua versi terakhir, termasuk Chrome di Android lama |

Situs Pemkab Mahakam Ulu sudah mencantumkan "Kantor Kecamatan Long Bagun" di daftar subdomainnya dengan tautan yang masih kosong. Alamat situs sebaiknya berupa subdomain `mahakamulukab.go.id` dan diurus bersama Diskominfo.

## 10. Aturan untuk AI pembangun dan isian yang belum ada

AI pembangun tidak boleh mengarang fakta: setiap data yang tidak tertulis di bagian 6 ditampilkan sebagai teks pengganti dalam kurung siku dan tetap bisa diubah dari panel admin.

1. Pakai hanya nama, angka, dan tanggal yang tertulis di dokumen ini.
2. Untuk data yang belum ada, tulis teks pengganti persis seperti di tabel di bawah. Jangan mengisi dengan nama orang, nomor telepon, alamat, atau kutipan rekaan.
3. Untuk foto yang belum ada, tampilkan kotak abu-abu berlabel, misalnya `[FOTO: Kantor Camat]`. Jangan memakai foto stok atau gambar buatan AI yang seolah-olah menggambarkan Long Bagun atau pejabatnya.
4. Tampilkan tahun dan sumber di samping setiap angka statistik.
5. Tandai butir "cek" di bagian 6 dengan catatan admin, bukan catatan publik.
6. Jangan membuat berita, pengumuman, atau agenda contoh selain berita di bagian 6.8.
7. Bangun berurutan: kerangka dan pengaturan situs, beranda, Kampung, Layanan, Informasi, Data, Wisata, PPID, lalu panel admin.

| Teks pengganti | Data yang harus diisi kantor camat |
| --- | --- |
| `[NAMA CAMAT]` | Nama camat saat ini bila berbeda dari bagian 6.4 |
| `[NAMA SEKCAM]`, `[NAMA KASI ...]` | Sekretaris camat dan para kepala seksi |
| `[NAMA PETINGGI {KAMPUNG}]` | Petinggi 11 kampung |
| `[ALAMAT KANTOR]` | Alamat lengkap kantor camat |
| `[NOMOR TELEPON]`, `[NOMOR WHATSAPP]`, `[EMAIL RESMI]` | Kontak kantor |
| `[JAM PELAYANAN]` | Hari dan jam layanan |
| `[SEJARAH]`, `[VISI]`, `[MISI]` | Sejarah singkat, asal-usul nama Long Bagun, visi dan misi |
| `[BATAS UTARA]`, `[BATAS TIMUR]`, `[BATAS SELATAN]`, `[BATAS BARAT]` | Batas wilayah kecamatan |
| `[SYARAT]`, `[ALUR]`, `[WAKTU]`, `[BIAYA]` | Rincian tiap layanan sesuai SOP |
| `[JUMLAH SEKOLAH]`, `[JUMLAH PUSKESMAS]`, `[JUMLAH TEMPAT IBADAH]` | Fasilitas per kampung dari Kecamatan Long Bagun Dalam Angka 2025 |
| `[DOKUMEN PPID]` | Renstra, Renja, LKjIP, perjanjian kinerja, SOP |

## 11. Kriteria penerimaan

Situs dinyatakan siap diluncurkan bila kedua belas butir ini terpenuhi.

- [ ] Semua halaman di bagian 3 dapat dibuka dan tidak ada tautan mati
- [ ] Semua kebutuhan P1 di bagian 4 berjalan di ponsel 360 px dan desktop 1280 px
- [ ] Sebelas halaman kampung menampilkan angka yang sama persis dengan tabel 6.2
- [ ] Angka kunci beranda menampilkan 11 kampung, 17.017 jiwa (2025), 4.801,22 km², dan 58 RT beserta sumbernya, atau angka hasil verifikasi BPS
- [ ] Tidak ada nama, kontak, atau berita rekaan; semua yang belum terisi tampil sebagai teks pengganti
- [ ] Admin dapat menambah berita berfoto dan melihatnya terbit di beranda
- [ ] Pita peringatan darurat dapat dinyalakan dan dimatikan dari panel admin
- [ ] Formulir pengaduan mengirim email ke alamat kantor dan menolak kiriman kosong
- [ ] Beranda berbobot di bawah 1 MB
- [ ] Skor aksesibilitas dan kinerja Lighthouse masing-masing minimal 90 di ponsel
- [ ] Lambang kabupaten tampil di kepala halaman dan ikon tab
- [ ] Semua foto punya teks alternatif dan kredit

## 12. Sumber

Halaman berikut dibuka pada 8 Oktober 2026; sumber berita lain yang dipakai untuk data akses dan wisata tercantum di dokumen rancangan sebelumnya.

- [Wikipedia: Long Bagun](https://en.wikipedia.org/wiki/Long_Bagun) dan [Mahakam Ulu Regency](https://en.wikipedia.org/wiki/Mahakam_Ulu_Regency), merujuk publikasi BPS
- [Wikipedia Indonesia: Long Bagun, Mahakam Ulu](https://id.wikipedia.org/wiki/Long_Bagun,_Mahakam_Ulu)
- [BPS Mahakam Ulu: Kecamatan Long Bagun Dalam Angka 2025](https://mahulukab.bps.go.id/id/publication/2025/09/26/e4611161ca31da0dea3d32a2/kecamatan-long-bagun-dalam-angka-2025.html) (halaman katalog; PDF belum dibuka)
- [Portal Pemkab Mahakam Ulu](https://mahakamulukab.go.id/) dan [Prokopim Mahakam Ulu](https://prokopim.mahakamulukab.go.id/)
- [MediaKaltim: Musrenbang Long Bagun, 7 Februari 2026](https://mediakaltim.com/musrenbang-long-bagun-jadi-fondasi-penyusunan-rkpd-mahulu-2027/)
- [EksposKaltim: Kantor Camat Long Bagun, 17 Agustus 2017](https://eksposkaltim.com/berita-5594-kantor-camat-long-bagun-rampung-akhir-tahun-.html)
- [RRI: Festival Omin Atun Onam Adet](https://rri.co.id/samarinda/budaya/2478907/festival-omin-atun-onam-adet-jadi-agenda-tahunan-di-mahakam-ulu) (cuplikan hasil pencarian)
