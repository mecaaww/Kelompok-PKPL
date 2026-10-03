<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index()
    {
        $obat = Obat::orderBy('id')->paginate(12);

        return view('obat.index', compact('obat'));
    }

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
