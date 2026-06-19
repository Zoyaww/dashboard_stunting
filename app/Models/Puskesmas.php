<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Puskesmas extends Model
{
    protected $table = 'puskesmas';

    protected $fillable = ['nama'];

    public function rekapPuskesmas(): HasMany
    {
        return $this->hasMany(RekapPuskesmas::class);
    }
}