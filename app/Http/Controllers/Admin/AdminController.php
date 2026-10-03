<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Obat;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_pesanan' => Pesanan::count(),
            'pendapatan'    => Pesanan::where('status_pesanan', 'selesai')->sum('total_harga'),
            'obat_stok'     => Obat::where('stok', '<', 10)->count(),
            'user_aktif'    => User::where('role', 'pelanggan')->count(),
        ];

        $chartData = Pesanan::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as total')
        )
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->take(7)
        ->get();

        return view('admins.dashboard', compact('stats', 'chartData'));
    }
}
