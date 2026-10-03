<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    protected $table = 'keranjang';
    protected $fillable = ['user_id', 'obat_id', 'jumlah'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function obat() {
        return $this->belongsTo(Obat::class);
    }
}
