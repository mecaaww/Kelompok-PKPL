<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $produkDiskon = Obat::where('diskon_persen', '>', 0)
            ->where('stok', '>', 0)
            ->orderByDesc('diskon_persen')
            ->get();

        $query = Obat::query()->where('stok', '>', 0);

        if ($request->filled('kategori')) {
            $query->whereIn('kategori', $request->kategori);
        }

        if ($request->filled('brand')) {
            $query->where(function ($q) use ($request) {
                foreach ($request->brand as $brand) {
                    $q->orWhere('nama', 'LIKE', $brand . '%');
                }
            });
        }

        $produkSemua = $query
            ->orderBy('id')
            ->paginate(18)
            ->appends($request->query());

        if ($request->ajax()) {
            return view('partials._produk_grid', compact('produkSemua'))->render();
        }

        return view('home', compact('produkDiskon', 'produkSemua'));
    }
}
