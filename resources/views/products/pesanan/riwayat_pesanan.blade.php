@extends('layouts.app')

@section('title', 'Riwayat Pesanan – Apotek Alfina Rizqy')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-xl font-bold mb-6 flex items-center gap-2 text-gray-800">
        <iconify-icon icon="mdi:clipboard-text-clock-outline" class="text-[var(--primary)]"></iconify-icon>
        Riwayat Pesanan
    </h1>

    @if($riwayat->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-gray-100">
            <iconify-icon icon="mdi:cart-off" class="text-6xl text-gray-200 mb-4"></iconify-icon>
            <p class="text-gray-500 mb-6">Kamu belum pernah melakukan pemesanan.</p>
            <a href="{{ url('/') }}" class="bg-[var(--primary)] text-white px-8 py-3 rounded-xl font-bold hover:bg-[var(--primary-dark)] transition shadow-lg">
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($riwayat as $p)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex flex-wrap justify-between items-start gap-4 mb-4">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nomor Invoice</p>
                            <h3 class="font-bold text-gray-800">{{ $p->nomor_invoice }}</h3>
                            <p class="text-xs text-gray-500">{{ $p->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>

                        @php
                            $statusColor = [
                                'menunggu pembayaran' => 'bg-amber-100 text-amber-600',
                                'dikemas' => 'bg-blue-100 text-blue-600',
                                'dikirim' => 'bg-purple-100 text-purple-600',
                                'selesai' => 'bg-emerald-100 text-emerald-600',
                                'dibatalkan' => 'bg-red-100 text-red-600',
                            ][$p->status_pesanan] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <span class="{{ $statusColor }} px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                            {{ $p->status_pesanan }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center border-t border-gray-50 pt-4">
                        <div>
                            <p class="text-xs text-gray-400">Total Pembayaran</p>
                            <p class="font-bold text-[var(--primary)]">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</p>
                        </div>
                        <a href="{{ url('/pesanan/' . $p->id) }}" class="flex items-center gap-1 text-sm font-bold text-gray-600 hover:text-[var(--primary)] transition">
                            Lihat Detail
                            <iconify-icon icon="mdi:chevron-right"></iconify-icon>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
