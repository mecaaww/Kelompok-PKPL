<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::with('user')->latest();

        if ($request->has('status') && $request->status != '') {
            $query->where('status_pesanan', $request->status);
        }

        $orders = $query->get();
        return view('admins.pesanan', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update(['status_pesanan' => $request->status]);

        return redirect()->back()->with('success', 'Status pesanan #' . $pesanan->nomor_invoice . ' berhasil diubah menjadi ' . $request->status);
    }

    public function show($id)
    {
        $pesanan = Pesanan::with(['user', 'detailPesanans.obat'])->findOrFail($id);

        return view('admins.detail_pesanan', compact('pesanan'));
    }
}
