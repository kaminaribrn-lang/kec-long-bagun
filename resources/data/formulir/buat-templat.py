"""Membuat empat templat formulir layanan (HTML) untuk dicetak ke PDF lewat Chrome."""
import sys, os, html

out, logo = sys.argv[1], sys.argv[2]
os.makedirs(out, exist_ok=True)

CSS = """
@page{size:A4;margin:14mm 16mm 14mm 16mm}
*{box-sizing:border-box}
body{font-family:Arial,Helvetica,sans-serif;font-size:10.5pt;color:#000;margin:0;line-height:1.35}
.kop{display:flex;align-items:center;gap:14px;border-bottom:3px double #000;padding-bottom:8px;margin-bottom:10px}
.kop img{width:62px;height:auto}
.kop div{flex:1;text-align:center;padding-right:62px}
.kop b{display:block;font-size:13pt;letter-spacing:.3px}
.kop .besar{font-size:15pt}
.kop span{font-size:9pt}
h1{font-size:12.5pt;text-align:center;margin:8px 0 2px;text-transform:uppercase}
.no{text-align:center;font-size:9.5pt;margin-bottom:10px}
h2{font-size:10.5pt;margin:12px 0 5px;background:#e9e9e9;padding:3px 6px;border:1px solid #000}
table{width:100%;border-collapse:collapse}
.isi td{padding:3.5px 4px;vertical-align:bottom}
.isi td.l{width:38%}
.isi td.t{width:2%}
.isi td.g{border-bottom:1px dotted #000}
.kotak td,.kotak th{border:1px solid #000;padding:4px 5px;font-size:9.5pt;height:22px}
.kotak th{background:#f1f1f1}
.cek{display:grid;grid-template-columns:1fr 1fr;gap:3px 18px;margin:2px 0}
.cek.satu{grid-template-columns:1fr}
.cek span{display:flex;gap:7px;align-items:flex-start}
.cek i{flex:none;width:11px;height:11px;border:1px solid #000;margin-top:2px}
p{margin:6px 0}
.ttd{display:flex;justify-content:space-between;gap:20px;margin-top:14px;page-break-inside:avoid}
.ttd div{width:46%;text-align:center}
.ttd .ruang{height:54px}
.petugas{margin-top:12px;border:1px solid #000;padding:6px 8px;page-break-inside:avoid}
.petugas b{display:block;margin-bottom:3px}
.catatan{margin-top:8px;font-size:8.5pt;color:#333;border-top:1px solid #999;padding-top:4px}
"""


def baris(label):
    return f'<tr><td class="l">{html.escape(label)}</td><td class="t">:</td><td class="g">&nbsp;</td></tr>'


def isian(labels):
    return '<table class="isi">' + ''.join(baris(l) for l in labels) + '</table>'


def cek(butir, satu=False):
    return f'<div class="cek{" satu" if satu else ""}">' + ''.join(f'<span><i></i>{html.escape(b)}</span>' for b in butir) + '</div>'


def tabel(kepala, jumlah, lebar=None):
    th = ''.join(f'<th{" style=width:" + lebar[i] if lebar and lebar[i] else ""}>{html.escape(k)}</th>' for i, k in enumerate(kepala))
    tr = ''.join('<tr>' + ''.join(f'<td>{n + 1 if i == 0 else "&nbsp;"}</td>' for i in range(len(kepala))) + '</tr>' for n in range(jumlah))
    return f'<table class="kotak"><tr>{th}</tr>{tr}</table>'


PEMOHON = ['Nama lengkap', 'NIK', 'Nomor Kartu Keluarga', 'Tempat, tanggal lahir', 'Jenis kelamin', 'Pekerjaan', 'Alamat', 'RT / Kampung', 'Nomor telepon/WhatsApp']


def halaman(judul, kode, badan, ttd_kiri=None):
    kiri = ttd_kiri or ''
    return f"""<!doctype html><html lang="id"><head><meta charset="utf-8"><title>{html.escape(judul)}</title><style>{CSS}</style></head><body>
<div class="kop"><img src="{logo}" alt=""><div><b>PEMERINTAH KABUPATEN MAHAKAM ULU</b><b class="besar">KECAMATAN LONG BAGUN</b><span>Ujoh Bilang, Kabupaten Mahakam Ulu, Kalimantan Timur 75767</span></div></div>
<h1>{html.escape(judul)}</h1>
<div class="no">Nomor register: ........................................ (diisi petugas)</div>
{badan}
<p>Dengan ini saya menyatakan bahwa data di atas benar dan dokumen yang saya lampirkan sah. Saya bersedia menanggung akibat hukum bila pernyataan ini tidak benar.</p>
<div class="ttd"><div>{kiri}</div><div>Ujoh Bilang, ........................................ 20.....<br>Pemohon,<div class="ruang"></div>( ............................................................ )</div></div>
<div class="petugas"><b>Diisi petugas Kantor Camat</b>
<table class="isi">{baris('Tanggal diterima')}{baris('Kelengkapan berkas (lengkap / belum lengkap)')}{baris('Nama dan paraf petugas')}</table></div>
<div class="catatan">Templat formulir {html.escape(kode)}. Kantor Camat Long Bagun dapat menggantinya dengan formulir resmi.</div>
</body></html>"""


F = {}

F['formulir-pengantar-ktp-kk'] = halaman('Formulir Permohonan Layanan KTP-el dan Kartu Keluarga', 'LB-01',
    '<h2>A. Data pemohon</h2>' + isian(PEMOHON)
    + '<h2>B. Jenis permohonan (beri tanda centang)</h2>' + cek(['KTP-el baru', 'KK baru (membentuk keluarga baru)', 'KTP-el hilang atau rusak', 'KK hilang atau rusak', 'KTP-el karena perubahan data', 'KK karena perubahan data', 'KTP-el karena pindah datang', 'Pisah KK'])
    + '<h2>C. Berkas yang dilampirkan</h2>' + cek(['Kartu Keluarga', 'KTP-el lama atau KTP-el yang rusak', 'Buku nikah/kutipan akta perkawinan atau kutipan akta perceraian', 'Surat keterangan hilang dari kepolisian', 'Surat pernyataan tanggung jawab mutlak (bila perkawinan/perceraian belum tercatat)', 'Surat keterangan pindah', 'Bukti perubahan data (akta, ijazah, atau surat keterangan)', 'Lainnya: ......................................................'])
    + '<h2>D. Keterangan tambahan</h2>' + isian(['Keterangan', '']))

F['formulir-surat-pindah'] = halaman('Formulir Permohonan Pindah Penduduk', 'LB-02',
    '<h2>A. Data pemohon</h2>' + isian(PEMOHON[:6] + ['Nomor telepon/WhatsApp'])
    + '<h2>B. Alamat asal</h2>' + isian(['Alamat dan RT', 'Kampung / Kecamatan', 'Kabupaten / Provinsi'])
    + '<h2>C. Alamat tujuan</h2>' + isian(['Alamat dan RT/RW', 'Desa/Kelurahan/Kampung', 'Kecamatan', 'Kabupaten/Kota dan Provinsi'])
    + '<h2>D. Keterangan kepindahan</h2>' + isian(['Alasan pindah', 'Rencana tanggal pindah'])
    + '<p><b>Klasifikasi pindah:</b></p>' + cek(['Dalam satu kampung', 'Antarkampung dalam satu kecamatan', 'Antarkecamatan dalam satu kabupaten', 'Antarkabupaten/kota dalam satu provinsi', 'Antarprovinsi'])
    + '<p><b>Yang pindah:</b></p>' + cek(['Kepala keluarga dan seluruh anggota', 'Kepala keluarga saja', 'Sebagian anggota keluarga'])
    + '<h2>E. Anggota keluarga yang pindah</h2>' + tabel(['No', 'NIK', 'Nama lengkap', 'Hubungan keluarga'], 6, ['7%', '28%', '', '24%'])
    + '<h2>F. Berkas yang dilampirkan</h2>' + cek(['Kartu Keluarga', 'Surat pernyataan tidak keberatan dari pemilik rumah (bila menempati rumah bukan milik sendiri)', 'Lainnya: ......................................................'], True))

F['formulir-legalisasi-dokumen'] = halaman('Formulir Permohonan Legalisasi Dokumen', 'LB-03',
    '<h2>A. Data pemohon</h2>' + isian(['Nama lengkap', 'NIK', 'Alamat', 'RT / Kampung', 'Nomor telepon/WhatsApp'])
    + '<h2>B. Dokumen yang dimohonkan legalisasi</h2>' + tabel(['No', 'Jenis dokumen', 'Nomor dan tanggal dokumen', 'Instansi penerbit', 'Jumlah lembar'], 6, ['7%', '25%', '26%', '', '13%'])
    + '<h2>C. Keperluan</h2>' + isian(['Legalisasi dipakai untuk', ''])
    + '<h2>D. Berkas yang dibawa</h2>' + cek(['Dokumen asli (ditunjukkan kepada petugas)', 'Fotokopi dokumen yang akan dilegalisasi', 'Fotokopi KTP-el pemohon', 'Surat kuasa (bila diwakilkan)', 'Lainnya: ......................................................'], True))

F['formulir-rekomendasi-izin-usaha'] = halaman('Formulir Permohonan Rekomendasi Izin Usaha', 'LB-04',
    '<h2>A. Data pemohon</h2>' + isian(['Nama lengkap', 'NIK', 'Alamat tempat tinggal', 'RT / Kampung', 'Nomor telepon/WhatsApp'])
    + '<h2>B. Data usaha</h2>' + isian(['Nama usaha', 'Jenis/bidang usaha', 'Alamat tempat usaha', 'RT / Kampung', 'Mulai berusaha sejak', 'Jumlah tenaga kerja', 'Perkiraan modal usaha', 'Nomor Induk Berusaha (bila sudah ada)'])
    + '<p><b>Status tempat usaha:</b></p>' + cek(['Milik sendiri', 'Sewa/kontrak', 'Pinjam pakai', 'Lainnya: ..............................'])
    + '<h2>C. Keperluan rekomendasi</h2>' + isian(['Rekomendasi dipakai untuk', ''])
    + '<h2>D. Berkas yang dilampirkan</h2>' + cek(['Fotokopi KTP-el pemohon', 'Fotokopi Kartu Keluarga', 'Surat keterangan usaha dari petinggi kampung', 'Bukti kepemilikan atau sewa tempat usaha', 'Pasfoto pemohon', 'Lainnya: ......................................................']),
    'Mengetahui,<br>Petinggi Kampung ..............................<div class="ruang"></div>( ............................................................ )')

for nama, isi in F.items():
    open(os.path.join(out, nama + '.html'), 'w', encoding='utf-8').write(isi)
    print(nama)
