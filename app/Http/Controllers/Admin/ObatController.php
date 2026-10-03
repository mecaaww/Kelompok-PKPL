<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ObatController extends Controller
{
    public function index(Request $request)
    {
        $query = Obat::query();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'price_low') {
                $query->orderBy('harga', 'asc');
            } elseif ($request->sort === 'price_high') {
                $query->orderBy('harga', 'desc');
            } elseif ($request->sort === 'latest') {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        if ($request->has('diskon') && $request->diskon == '1') {
            $query->where('diskon_persen', '>', 0);
        }

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('low_stok')) {
            $query->where('stok', '<', 5);
        }

        $obats = $query->latest()->paginate(10)->withQueryString();

        return view('admins.produk', compact('obats'));
    }

    public function create()
    {
        return view('admins.create_product');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'gambar' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $data['path_gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        Obat::create($data);
        return redirect()->route('obat.index')->with('success', 'Produk berhasil ditambah');
    }

    public function edit(Obat $obat)
    {
        return view('admins.edit_product', compact('obat'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
        ]);

        $data = $request->except(['_token', '_method']);

        $obat = \App\Models\Obat::findOrFail($id);

        if ($request->hasFile('path_gambar')) {
            if ($obat->path_gambar && Storage::exists('public/' . $obat->path_gambar)) {
                Storage::delete('public/' . $obat->path_gambar);
            }
            $path = $request->file('path_gambar')->store('produk', 'public');
            $data['path_gambar'] = $path;
        }

        $obat->update($data);

        return redirect()->route('obat.index')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Obat $obat)
    {
        if ($obat->path_gambar) Storage::disk('public')->delete($obat->path_gambar);
        $obat->delete();
        return redirect()->route('obat.index')->with('success', 'Produk berhasil dihapus');
    }
}
