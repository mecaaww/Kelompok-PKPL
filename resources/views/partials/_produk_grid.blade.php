<div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-8">

  @forelse ($produkSemua as $p)
    @php
      $harga = (int) ($p->harga ?? 0);
      $diskon = (int) ($p->diskon_persen ?? 0);
      $hargaDiskon = ($harga > 0 && $diskon > 0)
        ? (int) ($harga * (100 - $diskon) / 100)
        : $harga;
    @endphp

    <a href="{{ url('/obat/' . $p->id) }}" class="block">
      <div class="bg-white rounded-xl overflow-hidden
                  ring-1 ring-gray-200 shadow-md
                  hover:shadow-xl hover:ring-[var(--primary)]/30
                  transition-all duration-300 transform-gpu relative h-full flex flex-col">

        @if ($diskon > 0)
          <div class="absolute top-0 left-2 z-10">
            <div class="w-12 h-12 bg-red-500 text-white text-sm font-bold
                        flex items-center justify-center rounded-b-full shadow-sm">
              -{{ $diskon }}%
            </div>
          </div>
        @endif

        <div class="aspect-square bg-gray-50 flex items-center justify-center overflow-hidden">
          <img src="{{ asset('storage/' . $p->path_gambar) }}"
               class="w-full h-full object-contain opacity-0 transition-opacity duration-300"
               onload="this.classList.remove('opacity-0')"
               alt="{{ $p->nama }}"
               width="200"
               height="200"
               loading="lazy"
               decoding="async">
        </div>

        <div class="w-full h-px bg-gray-100"></div>

        <div class="p-3 text-[13px] flex-1 flex flex-col">
          <p class="font-medium line-clamp-2 h-10 text-gray-800">
            {{ $p->nama }}
          </p>
          <p class="text-[12px] text-gray-400 mt-1">
            {{ $p->kategori }}
          </p>

          <div class="mt-auto pt-2">
            @if ($harga > 0)
              <p class="text-[var(--primary)] font-bold">
                Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
              </p>
              @if ($diskon > 0)
                <p class="line-through text-[11px] text-gray-400">
                  Rp {{ number_format($harga, 0, ',', '.') }}
                </p>
              @endif
            @else
              <p class="text-gray-400 text-[12px]">
                Harga belum tersedia
              </p>
            @endif
          </div>
        </div>

      </div>
    </a>

  @empty
    <div class="col-span-full py-10 text-center">
      <p class="text-gray-500 text-sm">
        Produk tidak ditemukan.
      </p>
    </div>
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
