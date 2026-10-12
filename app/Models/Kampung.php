<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kampung extends Model
{
    protected $fillable = ['nama','slug','kode_wilayah','luas_km2','penduduk','tahun_data','sumber_data','jumlah_rt','nama_petinggi','foto_petinggi','uraian','potensi','foto_utama','foto_alt','foto_kredit','galeri','alamat_kantor','kontak','latitude','longitude','catatan_admin','status'];

    protected $casts = [
        'galeri' => 'array',
        'status' => 'boolean',
        'luas_km2' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function destinasis(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Destinasi::class);
    }

    // Kepadatan dihitung dari penduduk dan luas agar selalu konsisten dengan dua angka itu.
    public function getKepadatanAttribute(): ?float
    {
        return ($this->luas_km2 > 0 && $this->penduduk !== null) ? round($this->penduduk / $this->luas_km2, 1) : null;
    }
}
