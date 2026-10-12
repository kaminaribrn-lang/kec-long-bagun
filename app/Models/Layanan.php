<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $fillable = ['nama','slug','kategori','ringkasan','syarat','alur','dasar_hukum','waktu','biaya','formulir','kontak_petugas','ikon','tampil_beranda','urutan','status'];

    protected $casts = [
        'syarat' => 'array',
        'alur' => 'array',
        'tampil_beranda' => 'boolean',
        'status' => 'boolean',
    ];
}
