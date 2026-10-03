@extends('layouts.admin')

@section('title', 'Tambah Obat – Alfina Rizqy')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('obat.index') }}" class="w-10 h-10 flex items-center justify-center bg-white rounded-xl shadow-sm border border-gray-200 text-gray-600 hover:text-[var(--primary)] hover:border-[var(--primary)] transition-all">
            <iconify-icon icon="mdi:arrow-left" class="text-xl"></iconify-icon>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-800">Tambah Produk Baru</h1>
            <p class="text-gray-500 text-sm mt-1">Lengkapi detail informasi produk di bawah ini</p>
        </div>
    </div>

    <form action="{{ route('obat.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf

        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-md border border-gray-200 space-y-5">
            <div class="space-y-2">
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Nama Produk *</label>
                <input type="text" name="nama" required
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:border-[var(--primary)] focus:ring-2 focus:ring-[var(--primary)]/20 outline-none text-sm font-semibold text-gray-700"
                       placeholder="Contoh: Amoxicillin 500mg">
            </div>

            <div class="space-y-2">
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Kategori *</label>
                <select name="kategori" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:border-[var(--primary)] focus:ring-2 focus:ring-[var(--primary)]/20 outline-none text-sm font-semibold text-gray-700">
                    <option value="">Pilih Kategori</option>
                    <option value="Alat Kesehatan">Alat Kesehatan</option>
                    <option value="Obat Bebas">Obat Bebas</option>
                    <option value="Obat Keras">Obat Keras</option>
                    <option value="Perawatan Tubuh">Perawatan Tubuh</option>
                    <option value="Vitamin dan Suplemen">Vitamin dan Suplemen</option>
                    <option value="Makanan dan Minuman">Makanan dan Minuman</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Harga Satuan (Rp) *</label>
                    <input type="number" name="harga" required
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:border-[var(--primary)] focus:ring-2 focus:ring-[var(--primary)]/20 outline-none text-sm font-semibold text-gray-700"
                           placeholder="0" min="0">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Stok Awal *</label>
                    <input type="number" name="stok" required
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:border-[var(--primary)] focus:ring-2 focus:ring-[var(--primary)]/20 outline-none text-sm font-semibold text-gray-700"
                           placeholder="0" min="0">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Diskon (%)</label>
                <input type="number" name="diskon_persen"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:border-[var(--primary)] focus:ring-2 focus:ring-[var(--primary)]/20 outline-none text-sm font-semibold text-gray-700"
                       placeholder="0" value="0" min="0" max="100">
                <p class="text-xs text-gray-500">Opsional: Kosongkan atau isi 0 jika tidak ada diskon</p>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('obat.index') }}"
                   class="flex-1 text-center px-6 py-3 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-[var(--primary)] text-white px-6 py-3 rounded-xl text-sm font-semibold hover:bg-[var(--primary-dark)] transition shadow-md">
                    Simpan Produk
                </button>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 space-y-4">
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide block">Foto Produk *</label>

                <div class="relative group">
                    <div id="preview-container"
                         class="w-full aspect-square bg-gray-50 rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden cursor-pointer hover:border-[var(--primary)] transition">
                        <div id="placeholder-content" class="text-center">
                            <iconify-icon icon="mdi:camera-plus-outline" class="text-5xl text-gray-300 mb-2"></iconify-icon>
                            <p class="text-xs text-gray-400 font-medium">Klik untuk upload</p>
                        </div>
                        <img id="image-preview" class="hidden w-full h-full object-contain p-2">
                    </div>
                    <input type="file" name="gambar" id="gambar-input" required
                           class="absolute inset-0 opacity-0 cursor-pointer"
                           accept="image/*">
                </div>

                <div class="space-y-1">
                    <p class="text-xs text-gray-500 font-medium flex items-center gap-2">
                        <iconify-icon icon="mdi:information-outline" class="text-sm"></iconify-icon>
                        Format: JPG, PNG, JPEG
                    </p>
                    <p class="text-xs text-gray-500 font-medium flex items-center gap-2">
                        <iconify-icon icon="mdi:file-outline" class="text-sm"></iconify-icon>
                        Ukuran maksimal: 2MB
                    </p>
                </div>

                <button type="button" id="remove-image"
                        class="hidden w-full px-4 py-2 rounded-lg bg-red-50 text-red-600 text-xs font-semibold hover:bg-red-100 transition">
                    <iconify-icon icon="mdi:trash-can-outline" class="text-sm"></iconify-icon>
                    Hapus Foto
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    const input = document.getElementById('gambar-input');
    const preview = document.getElementById('image-preview');
    const placeholder = document.getElementById('placeholder-content');
    const removeBtn = document.getElementById('remove-image');

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
        preview.src = '';
        preview.classList.add('hidden');
        placeholder.classList.remove('hidden');
        removeBtn.classList.add('hidden');
    });
</script>
@endsection
