<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destinasi extends Model
{
    protected $fillable = ['nama','slug','jenis','kampung_id','lokasi','uraian','cara_mencapai','waktu_terbaik','foto_utama','foto_alt','foto_kredit','galeri','catatan_admin','urutan','status'];

    protected $casts = [
        'galeri' => 'array',
        'status' => 'boolean',
    ];

    public const JENIS = ['alam' => 'Alam', 'budaya' => 'Budaya', 'acara_adat' => 'Acara adat'];

    public function kampung(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Kampung::class);
    }

    public function getJenisLabelAttribute(): string
    {
        return self::JENIS[$this->jenis] ?? ucfirst((string) $this->jenis);
    }
}
