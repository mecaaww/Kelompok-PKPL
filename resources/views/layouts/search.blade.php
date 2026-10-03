@extends('layouts.app')

@section('content')
<section class="max-w-6xl mx-auto px-4 py-6">

  <!-- Search Header -->
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-[var(--primary-dark)] mb-2">
      @if($search)
        Hasil Pencarian: "{{ $search }}"
      @else
        Pencarian Produk
      @endif
    </h1>
    <p class="text-gray-600 text-sm">
      Ditemukan <span class="font-semibold">{{ $totalResults }}</span> produk
    </p>
  </div>

  <!-- Search Bar & Filter -->
  <div class="bg-white rounded-xl shadow-md border border-[var(--primary)]/20 p-4 mb-6">
    <form action="{{ route('search') }}" method="GET" class="space-y-4">

      <!-- Search Input -->
      <div class="relative">
        <iconify-icon icon="mdi:magnify" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xl"></iconify-icon>
        <input
          type="text"
          name="q"
          value="{{ $search }}"
          placeholder="Cari nama produk, kategori, atau deskripsi..."
          class="w-full border rounded-lg py-3 pl-10 pr-4 text-sm outline outline-1 outline-gray-300 focus:outline-[var(--primary)]"
          autofocus>
      </div>

      <!-- Filter Kategori -->
      <div>
        <p class="font-medium text-sm mb-2 text-[var(--primary-dark)]">Filter Kategori</p>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
          @foreach (['Alat Kesehatan', 'Obat Bebas', 'Perawatan Tubuh', 'Vitamin dan Suplemen', 'Makanan dan Minuman'] as $kat)
            <label class="flex items-center gap-2 text-sm">
              <input
                type="checkbox"
                name="kategori[]"
                value="{{ $kat }}"
                class="accent-[var(--primary)]"
                {{ in_array($kat, (array)$kategori) ? 'checked' : '' }}>
              <span class="text-xs md:text-sm">{{ $kat }}</span>
            </label>
          @endforeach
        </div>
      </div>

      <!-- Buttons -->
      <div class="flex gap-2">
        <button type="submit" class="px-6 py-2 bg-[var(--primary)] text-white rounded-lg hover:bg-[var(--primary-dark)] transition text-sm font-medium">
          <iconify-icon icon="mdi:magnify" class="text-lg align-middle"></iconify-icon>
          Cari
        </button>
        <a href="{{ route('search') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition text-sm font-medium">
          Reset
        </a>
      </div>
    </form>
  </div>

  <!-- Results Grid -->
  @if($produk->count() > 0)
    <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-8">
      @foreach ($produk as $p)
        @php
          $harga = (int) ($p->harga ?? 0);
          $diskon = (int) ($p->diskon_persen ?? 0);
          $hargaDiskon = ($harga > 0 && $diskon > 0) ? (int) ($harga * (100 - $diskon) / 100) : $harga;
        @endphp
        <a href="{{ url('/obat/' . $p->id) }}" class="block">
          <div class="bg-white rounded-xl shadow-md overflow-hidden border border-[var(--primary)]/20 hover:shadow-lg transition relative h-full flex flex-col">
            @if ($diskon > 0)
              <div class="absolute top-0 left-2 z-10">
                <div class="w-12 h-12 bg-red-500 text-white text-sm font-bold flex items-center justify-center rounded-b-full">
                  -{{ $diskon }}%
                </div>
              </div>
            @endif
            <img src="{{ asset('storage/' . $p->path_gambar) }}" class="w-full aspect-square object-cover" width="200" height="200" loading="lazy">
            <div class="p-3 text-[13px] flex-1 flex flex-col">
              <p class="font-medium line-clamp-2 h-10">{{ $p->nama }}</p>
              <p class="text-[12px] text-gray-500 mt-1">{{ $p->kategori }}</p>
              <div class="mt-auto">
                @if ($harga > 0)
                  <p class="text-[var(--primary)] font-semibold">
                    Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                    @if ($diskon > 0)
                      <span class="line-through text-[11px] text-gray-400 block">
                        Rp {{ number_format($harga, 0, ',', '.') }}
                      </span>
                    @endif
                  </p>
                @else
                  <p class="text-gray-400 text-[12px]">Harga belum tersedia</p>
                @endif
              </div>
            </div>
          </div>
        </a>
      @endforeach
    </div>

    <!-- Pagination -->
    @if($produk->lastPage() > 1)
    <div class="flex items-center justify-between text-sm mt-6 border-t pt-6">
      <p class="text-gray-500">
        Halaman <span class="font-semibold">{{ $produk->currentPage() }}</span> dari <span class="font-semibold">{{ $produk->lastPage() }}</span>
      </p>

      @php
        $current = $produk->currentPage();
        $last = $produk->lastPage();
      @endphp

      <div class="flex items-center gap-1">
        @if ($current > 1)
          <a href="{{ $produk->previousPageUrl() }}" class="px-3 py-1 rounded-lg border bg-white hover:bg-gray-100 transition">&lt;</a>
        @else
          <span class="px-3 py-1 rounded-lg border text-gray-300 bg-gray-50 cursor-not-allowed">&lt;</span>
        @endif

        @if ($current == 1)
          <span class="px-3 py-1 rounded-lg bg-[var(--primary)] text-white font-medium">1</span>
        @else
          <a href="{{ $produk->url(1) }}" class="px-3 py-1 rounded-lg border bg-white hover:bg-gray-100 transition">1</a>
        @endif

        @if ($current > 3)
          <span class="px-2 text-gray-400">…</span>
        @endif

        @for ($i = max(2, $current - 1); $i <= min($last - 1, $current + 1); $i++)
          @if ($i == $current)
            <span class="px-3 py-1 rounded-lg bg-[var(--primary)] text-white font-medium">{{ $i }}</span>
          @else
            <a href="{{ $produk->url($i) }}" class="px-3 py-1 rounded-lg border bg-white hover:bg-gray-100 transition">{{ $i }}</a>
          @endif
        @endfor

        @if ($current < $last - 2)
          <span class="px-2 text-gray-400">…</span>
        @endif

        @if ($last > 1)
          @if ($current == $last)
            <span class="px-3 py-1 rounded-lg bg-[var(--primary)] text-white font-medium">{{ $last }}</span>
          @else
            <a href="{{ $produk->url($last) }}" class="px-3 py-1 rounded-lg border bg-white hover:bg-gray-100 transition">{{ $last }}</a>
          @endif
        @endif

        @if ($current < $last)
          <a href="{{ $produk->nextPageUrl() }}" class="px-3 py-1 rounded-lg border bg-white hover:bg-gray-100 transition">&gt;</a>
        @else
          <span class="px-3 py-1 rounded-lg border text-gray-300 bg-gray-50 cursor-not-allowed">&gt;</span>
        @endif
      </div>
    </div>
    @endif

  @else
    <!-- No Results -->
    <div class="text-center py-16">
      <iconify-icon icon="mdi:magnify-close" class="text-6xl text-gray-300 mb-4"></iconify-icon>
      <h3 class="text-xl font-semibold text-gray-600 mb-2">Produk Tidak Ditemukan</h3>
      <p class="text-gray-500 mb-6">Coba gunakan kata kunci lain atau ubah filter pencarian</p>
      <a href="{{ route('home') }}" class="inline-block px-6 py-2 bg-[var(--primary)] text-white rounded-lg hover:bg-[var(--primary-dark)] transition">
        Kembali ke Beranda
      </a>
    </div>
  @endif

</section>
@endsection
