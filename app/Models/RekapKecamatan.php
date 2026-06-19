<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekapKecamatan extends Model
{
    protected $fillable = [
        'kecamatan_id',
        'tahun',
        'jumlah_stunting',
        'jumlah_balita_diukur',
        'prevalensi_stunting',
    ];

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }
}