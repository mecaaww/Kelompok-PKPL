@extends('layouts.admin')

@section('title', 'Manajemen Pesanan – Alfina Rizqy')

@section('content')
<div class="space-y-6 pb-32">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Pesanan</h1>
            <p class="text-gray-500 text-sm mt-1">Pantau dan kelola pengiriman obat ke pelanggan</p>
        </div>

        <div class="relative min-w-[220px] w-full md:w-auto custom-dropdown">
            <button type="button" class="dropdown-trigger w-full pl-12 pr-10 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 text-left flex items-center hover:border-gray-300 shadow-sm">
                <iconify-icon icon="mdi:filter-variant" class="absolute left-4 text-gray-500"></iconify-icon>
                <span class="truncate">{{ request('status') ?: 'Semua Status' }}</span>
                <iconify-icon icon="mdi:chevron-down" class="absolute right-4 text-gray-500 transition-transform duration-300"></iconify-icon>
            </button>
            <div class="dropdown-menu hidden absolute top-full right-0 w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-lg z-[150] overflow-hidden">
                <form action="" method="GET" id="filterForm" class="p-2 space-y-1">
                    <a href="#" data-value="" class="dropdown-item block px-4 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:bg-red-50 hover:text-[var(--primary)]">Semua Status</a>
                    @foreach (['menunggu pembayaran', 'dikemas', 'dikirim', 'selesai', 'dibatalkan'] as $st)
                        <a href="#" data-value="{{ $st }}" class="dropdown-item block px-4 py-2 rounded-lg text-sm font-semibold {{ request('status') == $st ? 'bg-red-50 text-[var(--primary)]' : 'text-gray-600' }} hover:bg-red-50 hover:text-[var(--primary)]">{{ ucwords($st) }}</a>
                    @endforeach
                    <input type="hidden" name="status" value="{{ request('status') }}">
                </form>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="hidden md:block">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 text-xs font-semibold border-b border-gray-200">
                    <tr>
                        <th class="p-4">Invoice & Pelanggan</th>
                        <th class="p-4">Total Bayar</th>
                        <th class="p-4">Status Pesanan</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="p-4">
                            <p class="font-bold text-gray-800">{{ $p->nomor_invoice }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $p->user->username }} • {{ $p->created_at->format('d/m/y H:i') }}</p>
                        </td>
                        <td class="p-4">
                            <p class="text-sm font-semibold text-green-600">Rp{{ number_format($p->total_harga, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $p->metode_pembayaran }}</p>
                        </td>
                        <td class="p-4">
                            <div class="relative custom-dropdown inline-block min-w-[170px]">
                                @php
                                    $colorClass = match($p->status_pesanan) {
                                        'selesai' => 'bg-green-50 text-green-600 border-green-200',
                                        'dikemas' => 'bg-orange-50 text-orange-600 border-orange-200',
                                        'dikirim' => 'bg-blue-50 text-blue-600 border-blue-200',
                                        'dibatalkan' => 'bg-red-50 text-red-600 border-red-200',
                                        default => 'bg-amber-50 text-amber-600 border-amber-200',
                                    };
                                @endphp
                                <button type="button" class="dropdown-trigger w-full px-3 py-2 rounded-lg border font-bold text-xs flex items-center justify-between gap-2 shadow-sm {{ $colorClass }}">
                                    <span>{{ ucwords($p->status_pesanan) }}</span>
                                    <iconify-icon icon="mdi:chevron-down" class="transition-transform duration-300"></iconify-icon>
                                </button>

                                <div class="dropdown-menu hidden absolute top-full left-0 w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-lg z-[200] p-2">
                                    <form action="{{ url('admin/pesanan/'.$p->id.'/status') }}" method="POST" class="space-y-1">
                                        @csrf @method('PUT')
                                        @foreach(['menunggu pembayaran', 'dikemas', 'dikirim', 'selesai', 'dibatalkan'] as $statusOption)
                                            <button name="status" value="{{ $statusOption }}" class="w-full text-left px-3 py-2 rounded-lg text-xs font-bold hover:bg-gray-50 {{ $p->status_pesanan == $statusOption ? 'text-[var(--primary)] bg-red-50' : 'text-gray-600' }}">
                                                {{ ucwords($statusOption) }}
                                            </button>
                                        @endforeach
                                    </form>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <a href="{{ url('admin/pesanan/'.$p->id) }}" class="inline-flex w-9 h-9 items-center justify-center rounded-xl bg-blue-50 border border-blue-200 text-blue-600 hover:bg-blue-100">
                                <iconify-icon icon="mdi:eye-outline"></iconify-icon>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="p-10 text-center text-gray-500 font-semibold">Belum ada pesanan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="md:hidden divide-y divide-gray-100">
            @forelse($orders as $p)
            <div class="p-4 space-y-3">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-bold text-gray-800 text-sm">{{ $p->nomor_invoice }}</p>
                        <p class="text-xs text-gray-500">{{ $p->user->username }} • {{ $p->created_at->format('d/m/y H:i') }}</p>
                    </div>
                    <p class="font-bold text-green-600 text-sm">Rp{{ number_format($p->total_harga, 0, ',', '.') }}</p>
                </div>

                <div class="flex flex-col gap-2 bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <div class="relative custom-dropdown">
                        @php
                            $colorClassMob = match($p->status_pesanan) {
                                'selesai' => 'bg-green-50 text-green-600 border-green-200',
                                'dikemas' => 'bg-orange-50 text-orange-600 border-orange-200',
                                'dikirim' => 'bg-blue-50 text-blue-600 border-blue-200',
                                'dibatalkan' => 'bg-red-50 text-red-600 border-red-200',
                                default => 'bg-amber-50 text-amber-600 border-amber-200',
                            };
                        @endphp
                        <button type="button" class="dropdown-trigger w-full px-3 py-2 rounded-lg border font-bold text-xs flex items-center justify-between gap-2 shadow-sm bg-white {{ $colorClassMob }}">
                            <span>Status: {{ ucwords($p->status_pesanan) }}</span>
                            <iconify-icon icon="mdi:chevron-down" class="transition-transform duration-300"></iconify-icon>
                        </button>
                        <div class="dropdown-menu hidden absolute bottom-full left-0 w-full mb-2 bg-white border border-gray-200 rounded-xl shadow-lg z-[200] p-2">
                            <form action="{{ url('admin/pesanan/'.$p->id.'/status') }}" method="POST" class="space-y-1">
                                @csrf @method('PUT')
                                @foreach(['menunggu pembayaran', 'dikemas', 'dikirim', 'selesai', 'dibatalkan'] as $statusOption)
                                    <button name="status" value="{{ $statusOption }}" class="w-full text-left px-3 py-2 rounded-lg text-xs font-bold hover:bg-gray-50 {{ $p->status_pesanan == $statusOption ? 'text-[var(--primary)] bg-red-50' : 'text-gray-600' }}">
                                        {{ ucwords($statusOption) }}
                                    </button>
                                @endforeach
                            </form>
                        </div>
                    </div>
                    <a href="{{ url('admin/pesanan/'.$p->id) }}" class="flex items-center justify-center gap-2 py-2 px-4 bg-white border border-gray-200 text-gray-600 rounded-lg font-bold text-xs shadow-sm">
                        <iconify-icon icon="mdi:eye"></iconify-icon>
                        Lihat Detail
                    </a>
                </div>
            </div>
            @empty
            <div class="p-10 text-center text-gray-500 text-sm font-semibold">Belum ada pesanan</div>
            @endforelse
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
        const trigger = dropdown.querySelector('.dropdown-trigger');
        const menu = dropdown.querySelector('.dropdown-menu');
        const arrow = trigger.querySelector('iconify-icon[icon="mdi:chevron-down"]');
        const row = dropdown.closest('tr');

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();

            document.querySelectorAll('.dropdown-menu').forEach(m => {
                if(m !== menu) {
                    m.classList.add('hidden');
                    m.previousElementSibling.querySelector('iconify-icon[icon="mdi:chevron-down"]').classList.remove('rotate-180');
                    if(m.closest('tr')) m.closest('tr').style.zIndex = "auto";
                }
            });

            const isHidden = menu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');

            if(row) {
                row.style.zIndex = isHidden ? "auto" : "100";
                row.style.position = isHidden ? "static" : "relative";
            }
        });

        const filterItems = menu.querySelectorAll('.dropdown-item');
        filterItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const input = dropdown.querySelector('input[name="status"]');
                input.value = item.getAttribute('data-value');
                dropdown.querySelector('#filterForm').submit();
            });
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
        document.querySelectorAll('iconify-icon[icon="mdi:chevron-down"]').forEach(a => a.classList.remove('rotate-180'));
        document.querySelectorAll('tr').forEach(tr => {
            tr.style.zIndex = "auto";
            tr.style.position = "static";
        });
    });
</script>
@endsection
