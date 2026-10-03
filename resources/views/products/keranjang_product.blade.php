@extends('layouts.app')

@section('title', 'Keranjang Belanja – Apotek Alfina Rizqy')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-xl font-bold mb-6 flex items-center gap-2 text-black">
        <iconify-icon icon="mdi:cart-outline" class="text-[var(--primary)]"></iconify-icon>
        Keranjang Belanja
    </h1>

    <div id="cart-container">
        @php $totalProduk = 0; @endphp

        @if($items->isEmpty())
            <div class="bg-white rounded-xl shadow-md p-12 text-center border border-[var(--primary)]/20">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <iconify-icon icon="mdi:cart-remove" class="text-5xl text-gray-300"></iconify-icon>
                </div>
                <h2 class="text-base font-bold text-gray-800 mb-2">Keranjangmu Masih Kosong</h2>
                <p class="text-gray-500 mb-6 text-base">Sepertinya kamu belum menambahkan obat apapun ke dalam keranjang.</p>
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 bg-[var(--primary)] text-white px-8 py-3 rounded-xl font-bold hover:bg-[var(--primary-dark)] transition shadow-md">
                    <iconify-icon icon="mdi:pill"></iconify-icon>
                    Cari Obat Sekarang
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-8 space-y-3">
                    @foreach($items as $item)
                        @php
                            $p = $item->obat;
                            $harga = (int) ($p->harga ?? 0);
                            $diskon = (int) ($p->diskon_persen ?? 0);
                            $hargaDiskon = ($harga > 0 && $diskon > 0) ? (int) ($harga * (100 - $diskon) / 100) : $harga;
                            $subtotal = $hargaDiskon * $item->jumlah;
                            $totalProduk += $subtotal;
                            $gambarFinal = asset('storage/' . $p->path_gambar);
                        @endphp

                        <div id="item-{{ $item->id }}" class="bg-white rounded-xl overflow-hidden shadow-md border border-[var(--primary)]/20 hover:shadow-lg transition-all">
                            <div class="p-4 flex gap-4 items-center">
                                <a href="{{ url('/obat/' . $p->id) }}" class="flex-shrink-0">
                                    <div class="w-20 h-20 bg-gray-50 rounded-lg overflow-hidden border border-gray-100 flex items-center justify-center p-1">
                                        <img src="{{ $gambarFinal }}"
                                             class="w-full h-full object-contain"
                                             onerror="this.src='{{ asset('assets/images/no-image.png') }}'"
                                             loading="lazy">
                                    </div>
                                </a>

                                <div class="flex-1 min-w-0">
                                    <a href="{{ url('/obat/' . $p->id) }}" class="hover:text-[var(--primary)] transition">
                                        <h3 class="font-semibold text-gray-800 line-clamp-2 mb-1">{{ $p->nama }}</h3>
                                    </a>
                                    <p class="text-[12px] text-gray-400 mb-2">{{ $p->kategori }}</p>

                                    <div class="flex items-center gap-2">
                                        <p class="text-[var(--primary)] font-bold">
                                            Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                                        </p>
                                        @if ($diskon > 0)
                                            <span class="text-[11px] line-through text-gray-400">
                                                Rp {{ number_format($harga, 0, ',', '.') }}
                                            </span>
                                            <span class="text-[10px] bg-red-500 text-white px-2 py-0.5 rounded-full font-bold">
                                                -{{ $diskon }}%
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-col items-end gap-2">
                                    <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden h-9 shadow-sm">
                                        <button onclick="updateQty({{ $item->id }}, -1, {{ $hargaDiskon }})"
                                                class="px-3 bg-gray-50 hover:bg-gray-100 transition text-gray-600 font-bold">
                                            −
                                        </button>
                                        <span id="qty-{{ $item->id }}" class="px-4 text-sm font-semibold min-w-[40px] text-center">
                                            {{ $item->jumlah }}
                                        </span>
                                        <button onclick="updateQty({{ $item->id }}, 1, {{ $hargaDiskon }})"
                                                class="px-3 bg-gray-50 hover:bg-gray-100 transition text-gray-600 font-bold">
                                            +
                                        </button>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-900">
                                            Rp <span id="subtotal-{{ $item->id }}">{{ number_format($subtotal, 0, ',', '.') }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-gray-100"></div>
                            <div class="px-4 py-2 bg-gray-50 flex justify-end">
                                <button onclick="removeItem({{ $item->id }})"
                                        class="text-red-500 text-xs hover:text-red-600 font-medium flex items-center gap-1">
                                    <iconify-icon icon="mdi:trash-can-outline"></iconify-icon>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="lg:col-span-4">
                    <div class="bg-white rounded-xl shadow-md border border-[var(--primary)]/20 p-5 sticky top-28 space-y-5">
                        <div>
                            <h2 class="text-base font-bold mb-3 flex items-center gap-2 text-[var(--primary-dark)]">
                                <iconify-icon icon="mdi:map-marker-outline" class="text-[var(--primary)]"></iconify-icon>
                                Alamat Pengiriman
                            </h2>

                            <div class="space-y-3">
                                <div class="p-3 bg-red-50 rounded-lg border border-red-100">
                                    <p class="text-[10px] uppercase font-bold text-red-500 mb-1 tracking-wide">
                                        📍 Titik Lokasi (Maps)
                                    </p>
                                    <p id="label-koordinat" class="text-xs text-gray-600 mb-2 italic">
                                        Belum dipilih
                                    </p>
                                    <button type="button"
                                            onclick="openMapModal()"
                                            class="w-full flex items-center justify-center gap-1 text-[11px] font-bold py-2 bg-[var(--primary)] text-white rounded-lg hover:bg-[var(--primary-dark)] transition shadow-sm">
                                        <iconify-icon icon="mdi:map-search"></iconify-icon>
                                        PILIH DI MAPS
                                    </button>
                                    <input type="hidden" id="input-titik-lokasi" name="titik_lokasi">
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-700 mb-1 block">
                                        Alamat Lengkap *
                                    </label>
                                    <textarea id="alamat-lengkap"
                                              name="alamat_lengkap"
                                              rows="2"
                                              class="w-full text-xs p-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[var(--primary)]/20 focus:border-[var(--primary)] outline-none resize-none"
                                              placeholder="Contoh: Jl. Merdeka No. 123, RT 02 RW 05"></textarea>
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-700 mb-1 block">
                                        Detail Alamat
                                    </label>
                                    <input type="text"
                                           id="detail-alamat"
                                           name="detail_alamat"
                                           class="w-full text-xs p-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[var(--primary)]/20 focus:border-[var(--primary)] outline-none"
                                           placeholder="Contoh: Rumah cat hijau, samping indomaret">
                                </div>
                            </div>
                        </div>

                        <div class="w-full h-px bg-gray-200"></div>

                        <div>
                            <h3 class="text-sm font-bold text-gray-800 mb-3">Ringkasan Belanja</h3>

                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal Produk</span>
                                    <span class="font-semibold">Rp <span id="display-subtotal-produk">{{ number_format($totalProduk, 0, ',', '.') }}</span></span>
                                </div>

                                <div class="flex justify-between text-gray-600">
                                    <span>Ongkos Kirim (<span id="jarak-km" class="font-semibold text-[var(--primary)]">0</span> km)</span>
                                    <span class="font-semibold">Rp <span id="display-ongkir">0</span></span>
                                </div>

                                <div class="w-full h-px bg-gray-200 my-2"></div>

                                <div class="flex justify-between text-base font-bold text-gray-900">
                                    <span>Total Bayar</span>
                                    <span class="text-[var(--primary)]">Rp <span id="total-harga-semua">{{ number_format($totalProduk, 0, ',', '.') }}</span></span>
                                </div>
                            </div>

                            <button onclick="checkout()"
                                    class="w-full mt-4 bg-[var(--primary)] text-white py-3 rounded-xl font-bold hover:bg-[var(--primary-dark)] transition shadow-md active:scale-95 flex items-center justify-center gap-2">
                                <iconify-icon icon="mdi:cart-check" class="text-xl"></iconify-icon>
                                Lanjut ke Pembayaran
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div id="mapModal" class="fixed inset-0 bg-black/60 z-[10000] hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-2xl overflow-hidden shadow-2xl">
        <div class="p-4 border-b flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-[var(--primary-dark)]">Geser Pin ke Lokasi Anda</h3>
            <button onclick="closeMapModal()" class="text-2xl text-gray-400 hover:text-gray-600 transition">&times;</button>
        </div>
        <div id="map" class="w-full h-[400px]"></div>
        <div class="p-4 bg-gray-50 border-t flex gap-2">
            <button onclick="confirmLocation()" class="flex-1 bg-[var(--primary)] text-white py-2.5 rounded-lg font-bold hover:bg-[var(--primary-dark)] transition shadow-md">
                Gunakan Lokasi Ini
            </button>
            <button onclick="closeMapModal()" class="px-6 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                Batal
            </button>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let map, marker;
    const APOTEK_LAT = -7.91425;
    const APOTEK_LNG = 112.59606;
    const BIAYA_PER_KM = 2000;

    let subtotalProduk = {{ $totalProduk }};
    let ongkir = 0;
    let selectedLat, selectedLng;

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
    });

    function openMapModal() {
        document.getElementById('mapModal').classList.remove('hidden');
        setTimeout(() => {
            if (!map) {
                map = L.map('map').setView([APOTEK_LAT, APOTEK_LNG], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                marker = L.marker([APOTEK_LAT, APOTEK_LNG], {draggable: true}).addTo(map);
                map.locate({setView: true, maxZoom: 16});
                map.on('locationfound', (e) => marker.setLatLng(e.latlng));
            } else {
                map.invalidateSize();
            }
        }, 300);
    }

    function closeMapModal() {
        document.getElementById('mapModal').classList.add('hidden');
    }

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    async function confirmLocation() {
        const pos = marker.getLatLng();
        selectedLat = pos.lat;
        selectedLng = pos.lng;

        const jarak = calculateDistance(APOTEK_LAT, APOTEK_LNG, selectedLat, selectedLng);
        const jarakFix = jarak < 1 ? 1 : Math.ceil(jarak);
        ongkir = jarakFix * BIAYA_PER_KM;

        document.getElementById('jarak-km').innerText = jarakFix;
        document.getElementById('display-ongkir').innerText = formatRupiah(ongkir);
        document.getElementById('label-koordinat').innerText = `${selectedLat.toFixed(5)}, ${selectedLng.toFixed(5)}`;
        document.getElementById('input-titik-lokasi').value = `${selectedLat},${selectedLng}`;

        updateTotalHarga();

        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${selectedLat}&lon=${selectedLng}`);
            const data = await res.json();
            document.getElementById('alamat-lengkap').value = data.display_name;
        } catch (e) {
            document.getElementById('alamat-lengkap').value = `Koordinat: ${selectedLat}, ${selectedLng}`;
        }
        closeMapModal();
        Toast.fire({ icon: 'success', title: 'Lokasi berhasil ditentukan' });
    }

    function updateTotalHarga() {
        const grandTotal = subtotalProduk + ongkir;
        document.getElementById('total-harga-semua').innerText = formatRupiah(grandTotal);
        document.getElementById('display-subtotal-produk').innerText = formatRupiah(subtotalProduk);
    }

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    async function updateQty(id, change, hargaSatuan) {
        const qtySpan = document.getElementById(`qty-${id}`);
        const subtotalItemSpan = document.getElementById(`subtotal-${id}`);

        let newQty = parseInt(qtySpan.innerText) + change;
        if (newQty < 1) return removeItem(id);

        const response = await fetch("{{ url('/api/keranjang/update') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ id: id, change: change })
        });

        if (response.ok) {
            qtySpan.innerText = newQty;
            subtotalItemSpan.innerText = formatRupiah(newQty * hargaSatuan);
            subtotalProduk += (change * hargaSatuan);
            updateTotalHarga();
            Toast.fire({ icon: 'success', title: 'Keranjang diperbarui' });
        }
    }

    async function removeItem(id) {
        Swal.fire({
            title: 'Hapus item ini?',
            text: "Produk akan dikeluarkan dari keranjang belanja.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const response = await fetch("{{ url('/api/keranjang/hapus') }}/" + id, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                if(response.ok) {
                    Toast.fire({ icon: 'success', title: 'Item berhasil dihapus' });
                    setTimeout(() => location.reload(), 1000);
                }
            }
        });
    }

    function checkout() {
        const alamat = document.getElementById('alamat-lengkap').value.trim();
        const titik = document.getElementById('input-titik-lokasi').value;
        const detail = document.getElementById('detail-alamat').value.trim();

        if(!titik) {
            Swal.fire({
                icon: 'error',
                title: 'Lokasi Belum Dipilih',
                text: 'Silakan pilih lokasi pengiriman pada peta terlebih dahulu.'
            });
            return;
        }

        if(!alamat) {
            Swal.fire({
                icon: 'error',
                title: 'Alamat Kosong',
                text: 'Mohon isi alamat lengkap pengiriman Anda.'
            });
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Pesanan',
            html: `
                <div class="text-left text-sm space-y-2">
                    <p><strong>Alamat:</strong> ${alamat}</p>
                    <p><strong>Jarak:</strong> ${document.getElementById('jarak-km').innerText} km</p>
                    <p><strong>Ongkir:</strong> Rp ${document.getElementById('display-ongkir').innerText}</p>
                    <p><strong>Total:</strong> Rp ${document.getElementById('total-harga-semua').innerText}</p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Cek Kembali'
        }).then(async (result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses Pesanan...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading() }
                });

                try {
                    const response = await fetch("{{ url('/api/pesanan/simpan') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            lat: selectedLat,
                            lng: selectedLng,
                            alamat_lengkap: alamat,
                            detail_alamat: detail,
                            ongkir: ongkir,
                            total_bayar: subtotalProduk + ongkir,
                            metode_pembayaran: 'Transfer Bank'
                        })
                    });

                    const data = await response.json();

                    if(data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Pesanan Anda berhasil dibuat.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = "{{ url('/pembayaran') }}/" + data.order_id;
                        });
                    } else {
                        Swal.fire('Gagal', data.message || 'Terjadi kesalahan saat memproses pesanan.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Gagal terhubung ke server. Silakan coba lagi.', 'error');
                }
            }
        });
    }
</script>

<style>
    .leaflet-container {
        z-index: 1 !important;
    }

    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection
