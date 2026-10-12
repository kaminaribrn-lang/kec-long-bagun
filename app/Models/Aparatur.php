<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aparatur extends Model
{
    protected $fillable = [
        'name',
        'position',
        'rank',
        'grade',
        'photo',
        'order',
        'status',
        'parent_id',
    ];

    public function atasan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function bawahan(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order');
    }
}
