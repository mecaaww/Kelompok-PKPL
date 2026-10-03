<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PesananController extends Controller
{
    public function simpan(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Sesi berakhir'], 401);
        }

        return DB::transaction(function () use ($request, $user) {

            $items = Keranjang::with('obat')->where('user_id', $user->id)->get();

            if ($items->isEmpty()) {
                return response()->json(['status' => 'error', 'message' => 'Keranjang kosong'], 400);
            }

            $invoice = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(4));

            $pesanan = Pesanan::create([
                'nomor_invoice'     => $invoice,
                'user_id'           => $user->id,
                'latitude'          => $request->lat,
                'longitude'         => $request->lng,
                'alamat_lengkap'    => $request->alamat_lengkap,
                'detail_alamat'     => $request->detail_alamat,
                'ongkir'            => $request->ongkir,
                'total_harga'       => $request->total_bayar,
                'status_pesanan'    => 'menunggu pembayaran',
                'metode_pembayaran' => $request->metode_pembayaran ?? 'Transfer Bank',
            ]);

            foreach ($items as $item) {
                $hargaAsli = (int)$item->obat->harga;
                $diskonPersen = (int)($item->obat->diskon_persen ?? 0);
                $hargaFinal = $hargaAsli - ($hargaAsli * ($diskonPersen / 100));
                $subtotalItem = $hargaFinal * $item->jumlah;

                DetailPesanan::create([
                    'pesanan_id'           => $pesanan->id,
                    'obat_id'              => $item->obat_id,
                    'jumlah'               => $item->jumlah,
                    'harga_satuan_asli'    => $hargaAsli,
                    'persentase_diskon'    => $diskonPersen,
                    'harga_setelah_diskon' => $hargaFinal,
                    'subtotal'             => $subtotalItem,
                ]);

                $item->delete();
            }

            return response()->json([
                'status' => 'success',
                'order_id' => $pesanan->id,
                'invoice' => $invoice
            ]);
        });
    }

    public function struk($id)
    {
        $user = auth()->guard('api')->user();

        $pesanan = Pesanan::with('detailPesanans.obat')
                    ->where('id', $id)
                    ->where('user_id', $user->id)
                    ->firstOrFail();

        return view('products.struk', compact('pesanan'));
    }

    public function index()
    {
        $user = auth()->guard('api')->user();

        $riwayat = Pesanan::where('user_id', $user->id)
                          ->orderBy('created_at', 'desc')
                          ->get();

        return view('products.pesanan.riwayat_pesanan', compact('riwayat'));
    }

    public function show($id)
    {
        $user = auth()->guard('api')->user();

        $pesanan = Pesanan::with(['detailPesanans.obat'])
                          ->where('id', $id)
                          ->where('user_id', $user->id)
                          ->firstOrFail();

        return view('products.pesanan.struk', compact('pesanan'));
    }
}
