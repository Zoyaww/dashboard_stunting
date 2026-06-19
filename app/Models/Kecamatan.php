<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    protected $fillable = ['nama'];

    public function rekapKecamatans(): HasMany
    {
        return $this->hasMany(RekapKecamatan::class);
    }
}