@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $pesanan->nomor_invoice)

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom-5 duration-500 pb-64">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.pesanan.index') }}" class="w-12 h-12 flex items-center justify-center bg-white rounded-2xl shadow-sm border-2 border-slate-100 text-slate-400 hover:text-[var(--primary)] transition-all">
            <iconify-icon icon="mdi:arrow-left" class="text-2xl"></iconify-icon>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Detail Pesanan</h1>
            <p class="text-slate-500 text-sm font-medium">{{ $pesanan->nomor_invoice }} • {{ $pesanan->created_at->format('d F Y, H:i') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border-2 border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2 uppercase text-xs tracking-widest">
                        <iconify-icon icon="mdi:pill" class="text-[var(--primary)] text-xl"></iconify-icon>
                        Item Pesanan
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b">
                            <tr>
                                <th class="p-6 w-16 text-center">ID</th>
                                <th class="p-6">Produk</th>
                                <th class="p-6 text-center">Qty</th>
                                <th class="p-6 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($pesanan->detailPesanans as $detail)
                            <tr class="hover:bg-slate-50/30 transition-all">
                                <td class="p-6 text-center font-bold text-slate-400 text-xs">
                                    #{{ $detail->obat->id }}
                                </td>
                                <td class="p-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 bg-white rounded-2xl border-2 border-slate-50 p-2 flex-shrink-0 shadow-sm overflow-hidden">
                                            <img src="{{ asset('storage/'.$detail->obat->path_gambar) }}" class="w-full h-full object-contain" onerror="this.src='{{ asset('assets/images/no-image.png') }}'">
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-800 leading-tight text-sm truncate max-w-[200px]">{{ $detail->obat->nama }}</p>
                                            <p class="text-[10px] text-slate-400 font-black mt-1 uppercase tracking-widest">{{ $detail->obat->kategori }}</p>
                                            <p class="text-[10px] text-[var(--primary)] font-bold mt-0.5">Rp{{ number_format($detail->harga_satuan, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-6 text-center font-black text-slate-600">
                                    <span class="px-3 py-1 bg-slate-100 rounded-lg text-xs">{{ $detail->jumlah }}</span>
                                </td>
                                <td class="p-6 text-right font-black text-slate-800 text-sm">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-50/30 border-t-2 border-slate-100">
                            <tr>
                                <td colspan="3" class="p-6 text-right font-black text-slate-400 uppercase text-[10px] tracking-[0.2em]">Total Pembayaran</td>
                                <td class="p-6 text-right font-black text-2xl text-emerald-600 italic tracking-tight">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border-2 border-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Detail Pengiriman</p>
                <div class="flex gap-4">
                    <div class="w-12 h-12 bg-red-50 text-[var(--primary)] rounded-2xl flex items-center justify-center flex-shrink-0 border border-red-100">
                        <iconify-icon icon="mdi:map-marker-radius" class="text-2xl"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800 leading-relaxed">{{ $pesanan->alamat_lengkap }}</p>
                        <p class="text-xs text-slate-400 font-medium italic mt-1">{{ $pesanan->detail_alamat }}</p>

                        @if($pesanan->latitude && $pesanan->longitude)
                        <a href="https://www.google.com/maps?q={{ $pesanan->latitude }},{{ $pesanan->longitude }}" target="_blank" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-blue-50 text-blue-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-blue-100 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                            <iconify-icon icon="mdi:google-maps" class="text-lg"></iconify-icon>
                            Lacak Lokasi via Maps
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border-2 border-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Informasi Pembeli</p>
                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-3xl border-2 border-slate-100">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-slate-400 text-2xl border border-slate-200 shadow-sm">
                        <iconify-icon icon="mdi:account-circle"></iconify-icon>
                    </div>
                    <div class="min-w-0">
                        <p class="font-black text-slate-800 uppercase text-xs truncate">{{ $pesanan->user->username }}</p>
                        <p class="text-[10px] text-slate-400 font-bold truncate tracking-tight">{{ $pesanan->user->email }}</p>
                    </div>
                </div>
                <div class="mt-6 space-y-3">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">Metode</span>
                        <span class="font-black text-slate-700 uppercase tracking-tighter">{{ $pesanan->metode_pembayaran }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border-2 border-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Tindakan Admin</p>

                @php
                    $statusColor = match($pesanan->status_pesanan) {
                        'selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                        'dikemas' => 'bg-orange-50 text-orange-600 border-orange-100',
                        'dikirim' => 'bg-blue-50 text-blue-600 border-blue-100',
                        'dibatalkan' => 'bg-red-50 text-red-600 border-red-100',
                        default => 'bg-amber-50 text-amber-600 border-amber-100',
                    };
                @endphp

                <div class="flex items-center gap-4 p-4 rounded-2xl border-2 {{ $statusColor }} mb-6 shadow-sm">
                    <iconify-icon icon="mdi:alert-decagram-outline" class="text-3xl"></iconify-icon>
                    <div>
                        <p class="text-[9px] font-black uppercase opacity-60 leading-none">Status Saat Ini</p>
                        <p class="text-sm font-black uppercase tracking-wider mt-1">{{ $pesanan->status_pesanan }}</p>
                    </div>
                </div>

                <div class="relative custom-dropdown">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Ubah Status Pesanan</label>

                    <button type="button" class="dropdown-trigger w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl text-xs font-black text-slate-700 text-left uppercase tracking-wider flex items-center justify-between hover:border-slate-300 transition-all shadow-sm">
                        <span>{{ $pesanan->status_pesanan }}</span>
                        <iconify-icon icon="mdi:chevron-down" class="text-xl transition-transform duration-300"></iconify-icon>
                    </button>

                    <div class="dropdown-menu hidden absolute top-full left-0 w-full mt-2 bg-white border-2 border-slate-100 rounded-2xl shadow-2xl z-[200] p-2">
                        <form action="{{ url('admin/pesanan/'.$pesanan->id.'/status') }}" method="POST" id="statusForm" class="space-y-1">
                            @csrf @method('PUT')
                            @foreach(['menunggu pembayaran', 'dikemas', 'dikirim', 'selesai', 'dibatalkan'] as $st)
                                <button type="submit" name="status" value="{{ $st }}" class="w-full text-left px-4 py-3 rounded-xl text-[10px] font-black uppercase transition-all hover:bg-slate-50 {{ $pesanan->status_pesanan == $st ? 'text-[var(--primary)] bg-red-50' : 'text-slate-500' }}">
                                    {{ $st }}
                                </button>
                            @endforeach
                        </form>
                    </div>
                </div>

                <p class="text-[9px] text-slate-400 font-medium mt-4 text-center italic">Memilih status akan otomatis memperbarui data pesanan.</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
        const trigger = dropdown.querySelector('.dropdown-trigger');
        const menu = dropdown.querySelector('.dropdown-menu');
        const arrow = trigger.querySelector('iconify-icon[icon="mdi:chevron-down"]');

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
        document.querySelectorAll('iconify-icon[icon="mdi:chevron-down"]').forEach(a => a.classList.remove('rotate-180'));
    });
</script>
@endsection
