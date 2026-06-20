<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stunting extends Model
{
    protected $fillable = [
        'nama',
        'desa',
        'kecamatan',
        'latitude',
        'longitude',
        'status',
        'balita_2020', 'stunting_2020', 'prevalensi_2020',
        'balita_2021', 'stunting_2021', 'prevalensi_2021',
        'balita_2022', 'stunting_2022', 'prevalensi_2022',
        'balita_2023', 'stunting_2023', 'prevalensi_2023',
        'balita_2024', 'stunting_2024', 'prevalensi_2024',
        'balita_2025', 'stunting_2025', 'prevalensi_2025',
    ];
}