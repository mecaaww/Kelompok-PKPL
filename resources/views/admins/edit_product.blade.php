@extends('layouts.admin')

@section('title', 'Edit Obat – ' . $obat->nama)

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('obat.index') }}" class="w-10 h-10 flex items-center justify-center bg-white rounded-xl shadow-sm border border-gray-200 text-gray-600 hover:text-[var(--primary)] hover:border-[var(--primary)] transition-all">
            <iconify-icon icon="mdi:arrow-left" class="text-xl"></iconify-icon>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-800">Edit Produk</h1>
            <p class="text-gray-500 text-sm mt-1">Perbarui informasi untuk <span class="text-[var(--primary)] font-semibold">{{ $obat->nama }}</span></p>
        </div>
    </div>

    <form action="{{ route('obat.update', $obat->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf
        @method('PUT')

        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-md border border-gray-200 space-y-5">
            <div class="space-y-2">
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Nama Produk *</label>
                <input type="text" name="nama" value="{{ old('nama', $obat->nama) }}" required
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:border-[var(--primary)] focus:ring-2 focus:ring-[var(--primary)]/20 outline-none text-sm font-semibold text-gray-700">
            </div>

            <div class="space-y-2">
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Kategori *</label>
                <select name="kategori" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:border-[var(--primary)] focus:ring-2 focus:ring-[var(--primary)]/20 outline-none text-sm font-semibold text-gray-700">
                    <option value="">Pilih Kategori</option>
                    @foreach(['Alat Kesehatan', 'Obat Bebas', 'Obat Keras', 'Perawatan Tubuh', 'Vitamin dan Suplemen', 'Makanan dan Minuman'] as $kat)
                        <option value="{{ $kat }}" {{ old('kategori', $obat->kategori) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Harga Satuan (Rp) *</label>
                    <input type="number" name="harga" value="{{ old('harga', $obat->harga) }}" required
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:border-[var(--primary)] focus:ring-2 focus:ring-[var(--primary)]/20 outline-none text-sm font-semibold text-gray-700"
                           min="0">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Stok Tersedia *</label>
                    <input type="number" name="stok" value="{{ old('stok', $obat->stok) }}" required
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:border-[var(--primary)] focus:ring-2 focus:ring-[var(--primary)]/20 outline-none text-sm font-semibold text-gray-700"
                           min="0">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Diskon (%)</label>
                <input type="number" name="diskon_persen" value="{{ old('diskon_persen', $obat->diskon_persen) }}"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:border-[var(--primary)] focus:ring-2 focus:ring-[var(--primary)]/20 outline-none text-sm font-semibold text-gray-700"
                       min="0" max="100">
                <p class="text-xs text-gray-500">Opsional: Kosongkan atau isi 0 jika tidak ada diskon</p>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('obat.index') }}"
                   class="flex-1 text-center px-6 py-3 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-[var(--primary)] text-white px-6 py-3 rounded-xl text-sm font-semibold hover:bg-[var(--primary-dark)] transition shadow-md">
                    Perbarui Produk
                </button>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 space-y-4">
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide block">Foto Produk</label>

                <div class="relative group">
                    <div id="preview-container"
                         class="w-full aspect-square bg-gray-50 rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden cursor-pointer hover:border-[var(--primary)] transition">

                        <img id="image-preview"
                             src="{{ $obat->path_gambar ? asset('storage/' . $obat->path_gambar) : '' }}"
                             class="{{ $obat->path_gambar ? '' : 'hidden' }} w-full h-full object-contain p-2">

                        <div id="placeholder-content" class="{{ $obat->path_gambar ? 'hidden' : '' }} text-center">
                            <iconify-icon icon="mdi:camera-plus-outline" class="text-5xl text-gray-300 mb-2"></iconify-icon>
                            <p class="text-xs text-gray-400 font-medium">Klik untuk upload</p>
                        </div>
                    </div>
                    <input type="file" name="gambar" id="gambar-input"
                           class="absolute inset-0 opacity-0 cursor-pointer"
                           accept="image/*">
                </div>

                <div class="space-y-1">
                    <p class="text-xs text-gray-500 font-medium flex items-center gap-2">
                        <iconify-icon icon="mdi:information-outline" class="text-sm"></iconify-icon>
                        Klik untuk mengganti foto
                    </p>
                    <p class="text-xs text-gray-500 font-medium flex items-center gap-2">
                        <iconify-icon icon="mdi:file-outline" class="text-sm"></iconify-icon>
                        Format: JPG, PNG. Maks: 2MB
                    </p>
                </div>

                <button type="button" id="remove-image"
                        class="hidden w-full px-4 py-2 rounded-lg bg-red-50 text-red-600 text-xs font-semibold hover:bg-red-100 transition">
                    <iconify-icon icon="mdi:trash-can-outline" class="text-sm"></iconify-icon>
                    Hapus Foto Baru
                </button>
            </div>

            <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                <p class="text-xs font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <iconify-icon icon="mdi:database-outline"></iconify-icon>
                    Data Saat Ini
                </p>
                <div class="space-y-2 text-xs text-gray-600">
                    <div class="flex justify-between">
                        <span>ID Produk:</span>
                        <span class="font-semibold">#{{ $obat->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Stok Saat Ini:</span>
                        <span class="font-semibold {{ $obat->stok < 10 ? 'text-red-600' : 'text-green-600' }}">{{ $obat->stok }} Unit</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Terakhir Update:</span>
                        <span class="font-semibold">{{ $obat->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const input = document.getElementById('gambar-input');
    const preview = document.getElementById('image-preview');
    const placeholder = document.getElementById('placeholder-content');
    const removeBtn = document.getElementById('remove-image');
    const originalSrc = preview.src;

    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 2MB');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
                removeBtn.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    removeBtn.addEventListener('click', function() {
        input.value = '';

        if (originalSrc && originalSrc !== window.location.href) {
            preview.src = originalSrc;
            preview.classList.remove('hidden');
        } else {
            preview.src = '';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }

        removeBtn.classList.add('hidden');
    });
</script>
@endsection
