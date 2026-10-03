@extends('layouts.app')

@section('title', 'Pembayaran – Apotek Alfina Rizqy')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10 animate-in fade-in duration-500">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-[var(--primary)] p-8 text-white text-center">
            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                <iconify-icon icon="mdi:check-all" class="text-5xl text-white"></iconify-icon>
            </div>
            <h1 class="text-2xl font-black tracking-tight">Pesanan Berhasil Dibuat!</h1>
            <p class="opacity-80 text-xs font-bold uppercase tracking-[0.2em] mt-2">{{ $pesanan->nomor_invoice }}</p>
        </div>

        <div class="p-8 space-y-8">
            <div>
                <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2 text-sm uppercase tracking-wider">
                    <iconify-icon icon="mdi:map-marker-radius" class="text-[var(--primary)] text-xl"></iconify-icon>
                    Lokasi Pengiriman
                </h3>
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <p class="text-sm text-slate-700 leading-relaxed font-medium">{{ $pesanan->alamat_lengkap }}</p>
                    <p class="text-[11px] text-slate-400 mt-2 italic">{{ $pesanan->detail_alamat }}</p>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-6">
                <h3 class="font-bold text-slate-800 mb-4 text-sm uppercase tracking-wider flex items-center gap-2">
                    <iconify-icon icon="mdi:basket-outline" class="text-emerald-500 text-xl"></iconify-icon>
                    Rincian Produk
                </h3>
                <div class="space-y-4">
                    @foreach($pesanan->detailPesanans as $item)
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex flex-col">
                            <span class="text-slate-700 font-bold leading-tight">{{ $item->obat->nama }}</span>
                            <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-1">Qty: {{ $item->jumlah }}</span>
                        </div>
                        <span class="font-black text-slate-800 italic">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-slate-50 p-6 rounded-3xl space-y-3 border border-slate-100 shadow-inner">
                <div class="flex justify-between text-xs font-bold text-slate-400 uppercase tracking-widest">
                    <span>Ongkos Kirim</span>
                    <span>Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-black text-xl text-slate-800 pt-3 border-t border-slate-200">
                    <span class="text-sm self-center">TOTAL BAYAR</span>
                    <span class="text-[var(--primary)] tracking-tighter">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="border-2 border-dashed border-amber-200 bg-amber-50 p-6 rounded-3xl text-center">
                <p class="text-[10px] text-amber-700 mb-3 uppercase font-black tracking-[0.2em]">Metode: {{ $pesanan->metode_pembayaran }}</p>
                <p class="text-sm text-slate-600 font-medium">Silakan transfer ke rekening resmi kami:</p>
                <div class="my-4">
                    <p class="text-2xl font-black text-slate-800 tracking-widest underline decoration-[var(--primary)] decoration-4 underline-offset-8">0021-01-023456-78-9</p>
                    <p class="text-[10px] font-black text-slate-500 uppercase mt-4 tracking-widest">BANK BRI a.n Apotek Alfina Rizqy</p>
                </div>
                <p class="text-[10px] text-amber-600 italic font-medium">*Pesanan akan diproses setelah bukti transfer divalidasi admin.</p>
            </div>
        </div>

        <div class="p-6 bg-slate-50 border-t border-slate-100 flex gap-4">
            <a href="{{ url('/') }}" class="flex-1 py-4 bg-white border-2 border-slate-200 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center hover:bg-slate-50 transition-all shadow-sm">Kembali ke Beranda</a>
            <a href="{{ url('/pesanan') }}" class="flex-1 py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest text-center hover:bg-black transition-all shadow-xl shadow-slate-300">Cek Status Pesanan</a>
        </div>
    </div>
</div>
@endsection
