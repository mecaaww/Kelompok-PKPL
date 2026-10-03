<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeranjangController extends Controller
{
    public function tambah(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'obat_id' => 'required|exists:obat,id',
            'jumlah' => 'required|integer|min:1'
        ]);

        $keranjang = Keranjang::updateOrCreate(
            [
                'user_id' => $user->id,
                'obat_id' => $request->obat_id,
            ],
            [
                'jumlah' => DB::raw("jumlah + " . $request->jumlah)
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil ditambahkan ke keranjang',
            'data' => $keranjang
        ]);
    }

    public function index()
    {
        $user = auth()->guard('api')->user();

        $items = Keranjang::with('obat')
                    ->where('user_id', $user->id)
                    ->get();

        return view('products.keranjang_product', compact('items'));
    }

    public function update(Request $request)
    {
        $user = auth()->guard('api')->user();

        $item = Keranjang::where('id', $request->id)
                         ->where('user_id', $user->id)
                         ->firstOrFail();

        $item->jumlah += $request->change;

        if ($item->jumlah < 1) {
            $item->delete();
        } else {
            $item->save();
        }

        return response()->json(['status' => 'success']);
    }

    public function hapus($id)
    {
        $user = auth()->guard('api')->user();
        Keranjang::where('id', $id)->where('user_id', $user->id)->delete();
        return response()->json(['status' => 'success']);
    }
}
