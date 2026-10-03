<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeskripsiObat extends Model
{
    protected $table = 'deskripsi_obat';
    protected $fillable = ['obat_id', 'label', 'nilai', 'urutan'];
    public $timestamps = false;

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }
}
