@extends('layouts.app')

@section('title', 'Detail Pesanan – ' . $pesanan->nomor_invoice)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ url('/pesanan') }}" class="w-8 h-8 flex items-center justify-center rounded-xl bg-white shadow-md border border-[var(--primary)]/20 hover:shadow-lg transition">
            <iconify-icon icon="mdi:arrow-left" class="text-xl text-gray-600"></iconify-icon>
        </a>
        <h1 class="text-md font-bold text-gray-800">Detail Pesanan</h1>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-[var(--primary)]/20 overflow-hidden">
        <div class="p-6 bg-gray-50 border-b">
            <div class="text-center mb-4">
                <img src="{{ asset('assets/images/logo_apotek.png') }}" class="h-16 mx-auto mb-2" alt="Logo Apotek Alfina Rizqy">
                <p class="text-xs text-gray-500">Dusun Baton, Desa Patereman, Modung, Bangkalan</p>
            </div>

            <div class="flex justify-between items-center text-sm border-t border-b border-dashed border-gray-300 py-3">
                <div>
                    <p class="text-gray-500 text-xs">No. Invoice</p>
                    <p class="font-bold text-[var(--primary)]">{{ $pesanan->nomor_invoice }}</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-500 text-xs">Tanggal</p>
                    <p class="font-semibold text-gray-800">{{ $pesanan->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>

            <div class="flex justify-between items-center mt-3">
                <div>
                    <p class="text-gray-500 text-xs mb-1">Status</p>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase
                        {{ $pesanan->status_pesanan == 'selesai' ? 'bg-green-100 text-green-700 border border-green-200' :
                           ($pesanan->status_pesanan == 'diproses' ? 'bg-blue-100 text-blue-700 border border-blue-200' :
                           'bg-amber-100 text-amber-700 border border-amber-200') }}">
                        {{ $pesanan->status_pesanan }}
                    </span>
                </div>
                <div class="text-right">
                    <p class="text-gray-500 text-xs mb-1">Pembayaran</p>
                    <p class="font-bold text-gray-800 text-sm">{{ $pesanan->metode_pembayaran }}</p>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <div>
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 text-sm uppercase tracking-wide">
                    <iconify-icon icon="mdi:pill" class="text-[var(--primary)]"></iconify-icon>
                    Daftar Obat
                </h3>

                <div class="space-y-3">
                    @foreach($pesanan->detailPesanans as $item)
                    <div class="flex gap-3 pb-3 border-b border-dashed border-gray-200 last:border-0">
                        <div class="w-14 h-14 bg-gray-50 rounded-lg flex items-center justify-center p-1.5 border flex-shrink-0">
                            <img src="{{ asset('storage/' . $item->obat->path_gambar) }}" class="w-full h-full object-contain" onerror="this.src='{{ asset('assets/images/no-image.png') }}'" loading="lazy">
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 leading-tight line-clamp-2">{{ $item->obat->nama }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $item->jumlah }} × Rp {{ number_format($item->harga_setelah_diskon, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="text-right flex-shrink-0">
                            <p class="text-sm font-bold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="w-full h-px bg-gray-200"></div>

            <div>
                <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2 text-sm uppercase tracking-wide">
                    <iconify-icon icon="mdi:map-marker" class="text-red-500"></iconify-icon>
                    Alamat Pengiriman
                </h3>

                <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                    <p class="text-sm text-gray-800 leading-relaxed">{{ $pesanan->alamat_lengkap }}</p>
                    @if($pesanan->detail_alamat)
                    <p class="text-xs text-gray-600 mt-2 italic">Detail: {{ $pesanan->detail_alamat }}</p>
                    @endif

                    @if($pesanan->latitude && $pesanan->longitude)
                    <a href="https://www.google.com/maps?q={{ $pesanan->latitude }},{{ $pesanan->longitude }}" target="_blank" class="mt-3 inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-700 transition">
                        <iconify-icon icon="mdi:google-maps" class="text-base"></iconify-icon>
                        Lihat di Maps
                    </a>
                    @endif
                </div>
            </div>

            <div class="w-full h-px bg-gray-200"></div>

            <div>
                <h3 class="font-bold text-gray-800 mb-3 text-sm uppercase tracking-wide">Rincian Pembayaran</h3>

                @php $subtotalProduk = $pesanan->detailPesanans->sum('subtotal'); @endphp

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span class="font-semibold">Rp {{ number_format($subtotalProduk, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between text-gray-600">
                        <span>Ongkir</span>
                        <span class="font-semibold">Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</span>
                    </div>

                    <div class="border-t border-dashed border-gray-300 pt-3 flex justify-between items-center">
                        <span class="font-bold text-gray-900 text-base">TOTAL</span>
                        <span class="text-2xl font-bold text-[var(--primary)]">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($pesanan->status_pesanan == 'menunggu_konfirmasi')
        <div class="p-5 bg-gray-50 border-t">
            <a href="https://wa.me/6285172382846?text=Halo,%20saya%20ingin%20konfirmasi%20pesanan%20{{ $pesanan->nomor_invoice }}" target="_blank" class="w-full py-3 bg-green-500 text-white rounded-lg font-bold hover:bg-green-600 transition shadow-md flex items-center justify-center gap-2">
                <iconify-icon icon="mdi:whatsapp" class="text-xl"></iconify-icon>
                Hubungi Admin via WhatsApp
            </a>
        </div>
        @endif

        <div class="p-4 bg-gray-50 border-t text-center">
            <p class="text-xs text-gray-500">Terima kasih telah berbelanja di Apotek Alfina Rizqy</p>
        </div>
    </div>
</div>
@endsection
