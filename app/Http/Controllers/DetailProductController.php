<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class DetailProductController extends Controller
{
    public function show($id)
    {
        $obat = Obat::with('deskripsi')->findOrFail($id);

        $gallery = [
            asset('storage/' . $obat->path_gambar),
            asset('assets/images/alur_pengiriman.jpg'),
            asset('assets/images/thanks.png'),
        ];

        return view('products.detail_product', compact('obat', 'gallery'));
    }
}
