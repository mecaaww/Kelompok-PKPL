@extends('layouts.app')

@section('content')
  <!-- BANNER SECTION - TIDAK DIUBAH SAMA SEKALI -->
  <section class="max-w-6xl mx-auto px-4">
    <div class="relative w-full h-full rounded-xl overflow-hidden shadow-md group">

      <div id="hero" class="relative aspect-[1430/477] overflow-hidden rounded-xl">
          <img id="heroImg" src="./assets/images/banner_1.png"
              class="absolute inset-0 w-full h-full object-cover transition-all duration-500">
      </div>

      <button id="prevBtn"
        class="absolute left-3 top-1/2 -translate-y-1/2
              bg-black/40 text-white rounded-full
              w-10 h-10 grid place-items-center
              opacity-0 group-hover:opacity-100 transition">
        <iconify-icon icon="mdi:chevron-left" class="text-3xl"></iconify-icon>
      </button>

      <button id="nextBtn"
        class="absolute right-3 top-1/2 -translate-y-1/2
              bg-black/40 text-white rounded-full
              w-10 h-10 grid place-items-center
              opacity-0 group-hover:opacity-100 transition">
        <iconify-icon icon="mdi:chevron-right" class="text-3xl"></iconify-icon>
      </button>

    </div>
  </section>

  <!-- PRODUK DISKON SECTION - Fixed responsive buttons -->
  <section class="mt-10">
    <div class="max-w-6xl mx-auto px-4 mb-4">
      <h2 class="text-xl font-semibold text-[var(--primary-dark)]">
        Produk Diskon
      </h2>
    </div>

    <div class="relative max-w-6xl mx-auto px-4">
      <!-- Fixed: Buttons positioned inside on mobile, outside on desktop -->
      <button id="diskonPrev"
        class="absolute left-2 md:left-0 md:-translate-x-[120%] top-1/2 -translate-y-1/2
              bg-black/50 text-white rounded-full
              w-8 h-8 md:w-10 md:h-10 grid place-items-center
              shadow z-10 hover:bg-black/70 transition">
        <iconify-icon icon="mdi:chevron-left" class="text-2xl md:text-3xl"></iconify-icon>
      </button>
        <div id="diskonSlider"
            class="overflow-x-auto scroll-smooth no-scrollbar pb-4">
            <div id="diskonItems" class="grid grid-rows-1 grid-flow-col auto-cols-[170px]
                        md:auto-cols-[190px] gap-4">
                @forelse ($produkDiskon as $p)
                    @php
                    $harga = (int) ($p->harga ?? 0);
                    $diskon = (int) ($p->diskon_persen ?? 0);
                    $hargaDiskon = ($harga > 0 && $diskon > 0) ? (int) ($harga * (100 - $diskon) / 100) : $harga;
                    @endphp

                    <a href="{{ url('/obat/' . $p->id) }}" class="block">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-[var(--primary)]/20 hover:shadow-lg transition relative">
                        @if ($diskon > 0)
                            <div class="absolute top-0 left-2 z-10">
                                <div class="w-12 h-12 bg-red-500 text-white text-sm font-bold flex items-center justify-center rounded-b-full">
                                -{{ $diskon }}%
                                </div>
                            </div>
                        @endif
                        <img src="{{ asset('storage/' . $p->path_gambar) }}" class="w-full aspect-square object-cover" width="200" height="200" alt="{{ $p->nama }}" loading="lazy">
                        <div class="w-full h-px bg-gray-200"></div>
                        <div class="p-3 text-[13px]">
                        <p class="font-medium line-clamp-2 h-10">{{ $p->nama }}</p>
                        <p class="text-[12px] text-gray-500 mt-1">{{ $p->kategori }}</p>

                        @if ($harga > 0)
                            <p class="text-[var(--primary)] mt-1 font-semibold">
                            Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                            @if ($diskon > 0)
                                <span class="line-through text-[12px] text-gray-500">
                                Rp {{ number_format($harga, 0, ',', '.') }}
                                </span>
                            @endif
                            </p>
                        @else
                            <p class="text-gray-400 mt-1 text-[12px]">Harga belum tersedia</p>
                        @endif
                        </div>
                    </div>
                    </a>
                @empty
                    <p class="text-gray-500 text-sm">Belum ada produk diskon.</p>
                @endforelse
            </div>
        </div>
        <button id="diskonNext"
            class="absolute right-2 md:right-0 md:translate-x-[120%] top-1/2 -translate-y-1/2
                    bg-black/50 text-white rounded-full
                    w-8 h-8 md:w-10 md:h-10 grid place-items-center
                    shadow z-10 hover:bg-black/70 transition">
            <iconify-icon icon="mdi:chevron-right" class="text-2xl md:text-3xl"></iconify-icon>
        </button>
    </div>
  </section>

  <section class="max-w-6xl mx-auto px-4 mt-14 mb-16">

    <div class="flex items-center justify-between mb-4">
      <h2 class="text-xl font-semibold text-[var(--primary-dark)]">
        Produk Kami
      </h2>

      <div class="relative" id="kategoriDropdown">
        <button type="button" id="filterButton"
          class="px-4 py-2 rounded-lg border border-red-300 bg-white shadow-sm
                hover:bg-red-50 flex items-center gap-2 text-sm text-[var(--primary)]">
          <iconify-icon icon="mdi:filter-outline"></iconify-icon>
          <span>Filter</span>
        </button>

        <form id="filterForm" method="GET" action="{{ route('home') }}">
          <div id="filterMenu"
            class="hidden absolute right-0 mt-2 w-72 bg-white border border-red-200
                  rounded-lg shadow-xl p-4 z-50 text-sm">

            <p class="text-[var(--primary-dark)] font-semibold mb-2">Filter</p>
            @php $selectedKategori = (array)request('kategori', []); @endphp

            <div class="mb-4">
              <p class="font-medium mb-1">Kategori</p>
              <div class="space-y-1">
                @foreach (['Alat Kesehatan', 'Obat Bebas', 'Perawatan Tubuh', 'Vitamin dan Suplemen', 'Makanan dan Minuman'] as $kat)
                  <label class="flex items-center gap-2">
                    <input type="checkbox" name="kategori[]" value="{{ $kat }}" class="accent-[var(--primary)]" {{ in_array($kat, $selectedKategori) ? 'checked' : '' }}>
                    {{ $kat }}
                  </label>
                @endforeach
              </div>
            </div>

            <div class="flex justify-between mt-4">
              <button type="submit" class="px-3 py-1.5 bg-[var(--primary)] text-white rounded-lg hover:bg-[var(--primary-dark)] transition">Terapkan</button>
              <a href="{{ route('home') }}" class="px-3 py-1.5 border border-gray-300 rounded-lg hover:bg-gray-100">Reset</a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div id="produkContainer">
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-8">
            @forelse ($produkSemua as $p)
                @php
                    $harga = (int) ($p->harga ?? 0);
                    $diskon = (int) ($p->diskon_persen ?? 0);
                    $hargaDiskon = ($harga > 0 && $diskon > 0) ? (int) ($harga * (100 - $diskon) / 100) : $harga;
                @endphp
                <a href="{{ url('/obat/' . $p->id) }}" class="block product-card">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-[var(--primary)]/20 hover:shadow-lg transition relative h-full flex flex-col">
                        @if ($diskon > 0)
                            <div class="absolute top-0 left-2 z-10">
                                <div class="w-12 h-12 bg-red-500 text-white text-sm font-bold flex items-center justify-center rounded-b-full">-{{ $diskon }}%</div>
                            </div>
                        @endif
                        <img src="{{ asset('storage/' . $p->path_gambar) }}" class="w-full aspect-square object-cover" width="200" height="200" loading="lazy" decoding="async">
                        <div class="p-3 text-[13px] flex-1 flex flex-col">
                            <p class="font-medium line-clamp-2 h-10">{{ $p->nama }}</p>
                            <p class="text-[12px] text-gray-500 mt-1">{{ $p->kategori }}</p>
                            <div class="mt-auto">
                                @if ($harga > 0)
                                    <p class="text-[var(--primary)] font-semibold">Rp {{ number_format($hargaDiskon, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-gray-500 text-sm col-span-full">Produk tidak ditemukan.</p>
            @endforelse
        </div>
        <div class="flex items-center justify-between text-sm mt-6 border-t pt-6">
            <p class="text-gray-500">
                Halaman
                <span class="font-semibold">{{ $produkSemua->currentPage() }}</span>
                dari
                <span class="font-semibold">{{ $produkSemua->lastPage() }}</span>
            </p>

            @php
                $current = $produkSemua->currentPage();
                $last = $produkSemua->lastPage();
            @endphp

            @if ($last > 1)
            <div class="flex items-center justify-end gap-1 text-sm">

                @if ($current > 1)
                    <a href="{{ $produkSemua->previousPageUrl() }}"
                    class="px-3 py-1 rounded-lg border bg-white hover:bg-gray-100 transition">
                        &lt;
                    </a>
                @else
                    <span class="px-3 py-1 rounded-lg border text-gray-300 bg-gray-50 cursor-not-allowed">
                        &lt;
                    </span>
                @endif

                @if ($current == 1)
                    <span class="px-3 py-1 rounded-lg bg-[var(--primary)] text-white font-medium">1</span>
                @else
                    <a href="{{ $produkSemua->url(1) }}"
                    class="px-3 py-1 rounded-lg border bg-white hover:bg-gray-100 transition">1</a>
                @endif

                @if ($current > 3)
                    <span class="px-2 text-gray-400">…</span>
                @endif

                @for ($i = max(2, $current - 1); $i <= min($last - 1, $current + 1); $i++)
                    @if ($i == $current)
                        <span class="px-3 py-1 rounded-lg bg-[var(--primary)] text-white font-medium">
                            {{ $i }}
                        </span>
                    @else
                        <a href="{{ $produkSemua->url($i) }}"
                        class="px-3 py-1 rounded-lg border bg-white hover:bg-gray-100 transition">
                            {{ $i }}
                        </a>
                    @endif
                @endfor

                @if ($current < $last - 2)
                    <span class="px-2 text-gray-400">…</span>
                @endif

                @if ($last > 1)
                    @if ($current == $last)
                        <span class="px-3 py-1 rounded-lg bg-[var(--primary)] text-white font-medium">
                            {{ $last }}
                        </span>
                    @else
                        <a href="{{ $produkSemua->url($last) }}"
                        class="px-3 py-1 rounded-lg border bg-white hover:bg-gray-100 transition">
                            {{ $last }}
                        </a>
                    @endif
                @endif

                @if ($current < $last)
                    <a href="{{ $produkSemua->nextPageUrl() }}"
                    class="px-3 py-1 rounded-lg border bg-white hover:bg-gray-100 transition">
                        &gt;
                    </a>
                @else
                    <span class="px-3 py-1 rounded-lg border text-gray-300 bg-gray-50 cursor-not-allowed">
                        &gt;
                    </span>
                @endif

            </div>
            @endif
        </div>
    </div>
  </section>

  <section class="max-w-6xl mx-auto px-4 mt-20 mb-20">
    <div class="rounded-xl p-6">
      <h2 class="text-2xl font-semibold text-[var(--primary-dark)] text-center mb-8">Tentang Kami</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white shadow-md border border-[var(--primary)]/20 rounded-xl p-5 space-y-4">
          <div class="flex items-start gap-4">
            <iconify-icon icon="mdi:headset" class="text-5xl text-[var(--primary)]"></iconify-icon>
            <div>
              <p class="font-semibold text-[var(--primary-dark)] text-lg uppercase tracking-wide">Admin Alfina Rizqy</p>
              <p class="text-[var(--primary)] font-bold text-xl mt-1">0851 7238 2846</p>
              <p class="text-gray-500 text-sm">07:00 - 21:00 WIB (WhatsApp Only)</p>
              <a href="https://wa.me/6285172382846" target="_blank" class="inline-block mt-3 px-4 py-2 rounded-lg bg-[var(--primary)] text-white text-sm hover:bg-[var(--primary-dark)]">Chat via WhatsApp</a>
            </div>
          </div>
          <div class="w-full h-px bg-gray-200"></div>
          <div class="space-y-3 text-sm">
            <p class="flex items-center gap-3"><iconify-icon icon="mdi:map-marker" class="text-xl text-[var(--primary)]"></iconify-icon> Dusun Baton, Desa Patereman, Modung, Bangkalan</p>
            <p class="flex items-center gap-3"><iconify-icon icon="mdi:check-decagram" class="text-xl text-[var(--primary)]"></iconify-icon> 100% Produk Asli & Bergaransi</p>
          </div>
        </div>
        <div class="w-full h-80 rounded-lg overflow-hidden border border-[var(--primary)]/30 shadow-md">
          <iframe class="w-full h-full" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3762.4202756416253!2d113.0239261!3d-7.2097465!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd78b2132044b13%3A0x5e9da11481bdf570!2sAPOTEK%20ALFINA%20RIZQY!5e1!3m2!1sen!2sid!4v1765300678656!5m2!1sen!2sid" loading="lazy"></iframe>
        </div>
      </div>
    </div>
  </section>
@endsection
