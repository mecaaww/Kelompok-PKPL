<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';

    protected $fillable = [
        'nama',
        'kategori',
        'harga',
        'stok',
        'diskon_persen',
        'path_gambar'
    ];

    public $incrementing = false;
    protected $keyType = 'int';

    public function deskripsi()
    {
        return $this->hasMany(DeskripsiObat::class, 'obat_id', 'id')
                    ->orderBy('urutan');
    }
}
