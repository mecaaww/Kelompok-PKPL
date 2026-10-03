@extends('layouts.admin')

@section('title', 'Manajemen Produk – Alfina Rizqy')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Produk</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola stok dan informasi produk di apotek</p>
        </div>
        <a href="{{ route('obat.create') }}" class="w-full sm:w-auto bg-[var(--primary)] text-white px-6 py-3 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-[var(--primary-dark)] transition-all shadow-md">
            <iconify-icon icon="mdi:plus-circle" class="text-xl"></iconify-icon>
            Tambah Produk
        </a>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-md border border-gray-200">
        <form id="mainFilterForm" action="" method="GET" class="space-y-4">
            <div class="flex flex-col lg:flex-row gap-3">
                <div class="relative flex-1">
                    <iconify-icon icon="mdi:magnify" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></iconify-icon>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..." class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 focus:border-[var(--primary)] focus:ring-2 focus:ring-[var(--primary)]/20 outline-none">
                </div>

                <div class="flex flex-col md:flex-row gap-3">
                    <div class="relative min-w-full md:min-w-[200px] custom-dropdown">
                        <button type="button" class="dropdown-trigger w-full pl-12 pr-10 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 text-left flex items-center hover:border-gray-300">
                            <iconify-icon icon="mdi:tag-multiple" class="absolute left-4 text-gray-500"></iconify-icon>
                            <span class="truncate">{{ request('kategori') ?: 'Semua Kategori' }}</span>
                            <iconify-icon icon="mdi:chevron-down" class="absolute right-4 text-gray-500 transition-transform duration-300"></iconify-icon>
                        </button>
                        <div class="dropdown-menu hidden absolute top-full left-0 w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-lg z-[100] overflow-hidden">
                            <div class="p-2 space-y-1">
                                <a href="#" data-value="" class="dropdown-item block px-4 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:bg-red-50 hover:text-[var(--primary)]">Semua Kategori</a>
                                @foreach (['Alat Kesehatan', 'Obat Bebas', 'Obat Keras', 'Perawatan Tubuh', 'Vitamin dan Suplemen'] as $kat)
                                    <a href="#" data-value="{{ $kat }}" class="dropdown-item block px-4 py-2 rounded-lg text-sm font-semibold {{ request('kategori') == $kat ? 'bg-red-50 text-[var(--primary)]' : 'text-gray-600' }} hover:bg-red-50 hover:text-[var(--primary)]">{{ $kat }}</a>
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    </div>

                    <div class="relative min-w-full md:min-w-[180px] custom-dropdown">
                        <button type="button" class="dropdown-trigger w-full pl-12 pr-10 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 text-left flex items-center hover:border-gray-300">
                            <iconify-icon icon="mdi:sort-variant" class="absolute left-4 text-gray-500"></iconify-icon>
                            <span>{{ request('sort') == 'price_low' ? 'Harga Terendah' : (request('sort') == 'price_high' ? 'Harga Tertinggi' : 'Terbaru') }}</span>
                            <iconify-icon icon="mdi:chevron-down" class="absolute right-4 text-gray-500 transition-transform duration-300"></iconify-icon>
                        </button>
                        <div class="dropdown-menu hidden absolute top-full left-0 w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-lg z-[100] overflow-hidden">
                            <div class="p-2 space-y-1">
                                <a href="#" data-value="latest" class="dropdown-item block px-4 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:bg-red-50 hover:text-[var(--primary)]">Terbaru</a>
                                <a href="#" data-value="price_low" class="dropdown-item block px-4 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:bg-red-50 hover:text-[var(--primary)]">Harga Terendah</a>
                                <a href="#" data-value="price_high" class="dropdown-item block px-4 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:bg-red-50 hover:text-[var(--primary)]">Harga Tertinggi</a>
                            </div>
                        </div>
                        <input type="hidden" name="sort" value="{{ request('sort', 'latest') }}">
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-gray-100">
                <div class="flex flex-wrap items-center gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="diskon" value="1" {{ request('diskon') == '1' ? 'checked' : '' }} onchange="this.form.submit()" class="w-5 h-5 accent-[var(--primary)] rounded">
                        <span class="text-sm font-semibold text-gray-600">Diskon</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="low_stok" value="1" {{ request('low_stok') == '1' ? 'checked' : '' }} onchange="this.form.submit()" class="w-5 h-5 accent-orange-500 rounded">
                        <span class="text-sm font-semibold text-gray-600">Stok Tipis</span>
                    </label>
                </div>

                <div class="flex gap-2 w-full sm:w-auto">
                    <a href="{{ route('obat.index') }}" class="flex-1 sm:flex-none text-center px-6 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50">Reset</a>
                    <button type="submit" class="flex-1 sm:flex-none px-6 py-2.5 bg-gray-900 text-white rounded-xl text-sm font-semibold hover:bg-black shadow-md">Terapkan</button>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 text-xs font-semibold border-b border-gray-200">
                    <tr>
                        <th class="p-4 text-center">ID</th>
                        <th class="p-4">Produk</th>
                        <th class="p-4 text-center">Stok</th>
                        <th class="p-4">Harga</th>
                        <th class="p-4">Diskon</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($obats as $o)
                    <tr class="hover:bg-gray-50">
                        <td class="p-4 text-center text-gray-500 font-semibold text-sm">#{{ $o->id }}</td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-14 h-14 bg-gray-50 rounded-xl border border-gray-200 p-2 flex-shrink-0">
                                    <img src="{{ asset('storage/'.$o->path_gambar) }}" class="w-full h-full object-contain" onerror="this.src='{{ asset('assets/images/no-image.png') }}'">
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $o->nama }}</p>
                                    <p class="text-xs text-gray-500">{{ $o->kategori }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-base p-4 text-center">
                            <span class="px-3 py-1 rounded-full text-sm font-bold {{ $o->stok < 10 ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-green-50 text-green-600 border border-green-200' }}">
                                {{ $o->stok }}
                            </span>
                        </td>
                        <td class="text-sm p-4 font-medium text-gray-800">Rp{{ number_format($o->harga, 0, ',', '.') }}</td>
                        <td class="p-4">
                            <span class="text-[var(--primary)] font-bold bg-red-50 px-2 py-1 rounded-lg border border-red-100 text-xs">{{ $o->diskon_persen }}%</span>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('obat.edit', $o->id) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100">
                                    <iconify-icon icon="mdi:pencil-outline"></iconify-icon>
                                </a>
                                <form action="{{ route('obat.destroy', $o->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-600 text-white hover:bg-red-700 shadow-md">
                                        <iconify-icon icon="mdi:trash-can-outline"></iconify-icon>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="p-10 text-center text-gray-500 font-semibold">Data Kosong</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="md:hidden divide-y divide-gray-100">
            @forelse($obats as $o)
            <div class="p-4 space-y-3">
                <div class="flex gap-3">
                    <div class="w-16 h-16 bg-gray-50 rounded-xl border border-gray-200 p-2 flex-shrink-0">
                        <img src="{{ asset('storage/'.$o->path_gambar) }}" class="w-full h-full object-contain" onerror="this.src='{{ asset('assets/images/no-image.png') }}'">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-500">#{{ $o->id }} • {{ $o->kategori }}</p>
                        <p class="font-semibold text-gray-800 text-sm">{{ $o->nama }}</p>
                        <p class="text-sm font-bold text-gray-800 mt-1">Rp{{ number_format($o->harga, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between bg-gray-50 p-3 rounded-xl">
                    <span class="text-xs font-semibold {{ $o->stok < 10 ? 'text-red-600' : 'text-green-600' }}">Stok: {{ $o->stok }} Unit</span>
                    <div class="flex gap-2">
                        <a href="{{ route('obat.edit', $o->id) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600"><iconify-icon icon="mdi:pencil"></iconify-icon></a>
                        <form action="{{ route('obat.destroy', $o->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-600 text-white"><iconify-icon icon="mdi:trash-can"></iconify-icon></button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-10 text-center text-gray-500 font-semibold">Data tidak ditemukan</div>
            @endforelse
        </div>

        <div class="p-4 bg-gray-50 border-t border-gray-100">
            <div class="flex items-center justify-between text-sm">
                <p class="text-gray-600">
                    Halaman
                    <span class="font-semibold">{{ $obats->currentPage() }}</span>
                    dari
                    <span class="font-semibold">{{ $obats->lastPage() }}</span>
                </p>

                @php
                    $current = $obats->currentPage();
                    $last = $obats->lastPage();
                @endphp

                @if ($last > 1)
                <div class="flex items-center justify-end gap-1 text-sm">
                    @if ($current > 1)
                        <a href="{{ $obats->appends(request()->query())->previousPageUrl() }}" class="px-3 py-1 rounded-lg border bg-white hover:bg-gray-100 transition">
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
                        <a href="{{ $obats->appends(request()->query())->url(1) }}" class="px-3 py-1 rounded-lg border bg-white hover:bg-gray-100 transition">1</a>
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
                            <a href="{{ $obats->appends(request()->query())->url($i) }}" class="px-3 py-1 rounded-lg border bg-white hover:bg-gray-100 transition">
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
                            <a href="{{ $obats->appends(request()->query())->url($last) }}" class="px-3 py-1 rounded-lg border bg-white hover:bg-gray-100 transition">
                                {{ $last }}
                            </a>
                        @endif
                    @endif

                    @if ($current < $last)
                        <a href="{{ $obats->appends(request()->query())->nextPageUrl() }}" class="px-3 py-1 rounded-lg border bg-white hover:bg-gray-100 transition">
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
    </div>
</div>

<script>
    document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
        const trigger = dropdown.querySelector('.dropdown-trigger');
        const menu = dropdown.querySelector('.dropdown-menu');
        const input = dropdown.querySelector('input');
        const arrow = trigger.querySelector('iconify-icon[icon="mdi:chevron-down"]');

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            document.querySelectorAll('.dropdown-menu').forEach(m => { if (m !== menu) m.classList.add('hidden') });
            menu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        });

        menu.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                input.value = item.getAttribute('data-value');
                document.getElementById('mainFilterForm').submit();
            });
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
        document.querySelectorAll('.dropdown-trigger iconify-icon[icon="mdi:chevron-down"]').forEach(a => a.classList.remove('rotate-180'));
    });
</script>
@endsection
