<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q', '');
        $kategori = $request->get('kategori', []);

        $query = Obat::query()->where('stok', '>', 0);

        // Jika ada kata kunci pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', '%' . $search . '%')
                  ->orWhere('kategori', 'LIKE', '%' . $search . '%');
            });
        }

        // Filter kategori
        if (!empty($kategori)) {
            $query->whereIn('kategori', $kategori);
        }

        $produk = $query
            ->orderBy('nama')
            ->paginate(24)
            ->appends($request->query());

        $totalResults = $query->count();

        return view('layouts.search', compact('produk', 'search', 'kategori', 'totalResults'));
    }
}
