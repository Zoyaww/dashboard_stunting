<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekapPuskesmas extends Model
{
    protected $table = 'rekap_puskesmas';

    protected $fillable = [
        'puskesmas_id',
        'tahun',
        'tanggal_data',
        'sangat_kurang',
        'kurang',
        'berat_badan_normal',
        'risiko_lebih',
        'outlier_bbu',
        'sangat_pendek',
        'pendek',
        'normal_tbu',
        'tinggi',
        'outlier_tbu',
        'gizi_buruk',
        'gizi_kurang',
        'normal_bbtb',
        'risiko_gizi_lebih',
        'gizi_lebih',
        'obesitas',
        'outlier_bbtb',
        'stunting',
        'wasting',
        'underweight',
        'total_balita_ditimbang',
        'sasaran_riil_balita',
        'ds',
        'prev_stunted',
        'prev_wasting',
        'prev_underweight',
    ];

    protected $casts = [
        'tanggal_data' => 'datetime',
    ];

    public function puskesmas(): BelongsTo
    {
        return $this->belongsTo(Puskesmas::class);
    }
}