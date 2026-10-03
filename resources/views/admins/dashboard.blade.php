@extends('layouts.admin')

@section('title', 'Dashboard Admin – Alfina Rizqy')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Utama</h1>
            <p class="text-gray-500 text-sm mt-1">Monitoring performa apotek secara real-time</p>
        </div>
        <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-200 flex items-center gap-2">
            <iconify-icon icon="mdi:calendar" class="text-[var(--primary)]"></iconify-icon>
            <span class="text-xs font-semibold text-gray-600">{{ date('d F Y') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl shadow-md border border-gray-200 flex items-center gap-4 hover:shadow-lg transition-all">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-2xl">
                <iconify-icon icon="mdi:shopping-outline"></iconify-icon>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold">Pesanan</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_pesanan'] }}</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-md border border-gray-200 flex items-center gap-4 hover:shadow-lg transition-all">
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-2xl">
                <iconify-icon icon="mdi:wallet-outline"></iconify-icon>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold">Pendapatan</p>
                <p class="text-xl font-bold text-green-600">Rp{{ number_format($stats['pendapatan'], 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-md border border-gray-200 flex items-center gap-4 hover:shadow-lg transition-all">
            <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center text-2xl">
                <iconify-icon icon="mdi:alert-decagram-outline"></iconify-icon>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold">Stok Menipis</p>
                <p class="text-2xl font-bold text-red-600">{{ $stats['obat_stok'] }}</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-md border border-gray-200 flex items-center gap-4 hover:shadow-lg transition-all">
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-2xl">
                <iconify-icon icon="mdi:account-heart-outline"></iconify-icon>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold">Pelanggan</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['user_aktif'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-gray-800 flex items-center gap-3">
                <div class="w-10 h-10 bg-green-500 text-white rounded-xl flex items-center justify-center shadow-md">
                    <iconify-icon icon="mdi:chart-box-outline" class="text-xl"></iconify-icon>
                </div>
                Grafik Penjualan Mingguan
            </h3>
            <span class="text-xs font-semibold text-gray-500 border px-3 py-1 rounded-full">7 Hari Terakhir</span>
        </div>
        <div class="h-[350px]">
            <canvas id="orderChart"></canvas>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('orderChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData->pluck('date')) !!},
                datasets: [{
                    label: 'Pesanan',
                    data: {!! json_encode($chartData->pluck('total')) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#10b981',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { weight: '600' }, color: '#94a3b8' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { weight: '600' }, color: '#94a3b8' }
                    }
                }
            }
        });
    });
</script>
@endsection
