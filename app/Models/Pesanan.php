<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = [
        'nomor_invoice',
        'user_id',
        'latitude',
        'longitude',
        'alamat_lengkap',
        'detail_alamat',
        'ongkir',
        'total_harga',
        'status_pesanan',
        'metode_pembayaran',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }
}
