<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistik extends Model
{
    protected $fillable = ['topik','kelompok','label','nilai','satuan','tahun','sumber','tautan_sumber','keterangan','catatan_admin','urutan'];

    protected $casts = [
        'nilai' => 'float',
    ];

    public const TOPIK = ['kunci' => 'Angka kunci beranda', 'kependudukan' => 'Kependudukan', 'pendidikan' => 'Pendidikan', 'kesehatan' => 'Kesehatan', 'ekonomi' => 'Ekonomi'];
}
