<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin Panel – Apotek Alfina Rizqy')</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    :root {
      --primary: #A80505;
      --primary-dark: #6C0000;
    }
    body { font-family: 'Poppins', sans-serif; background-color: #F8FAFC; }

    .sidebar-scroll::-webkit-scrollbar { width: 5px; }
    .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
    .sidebar-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }

    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
  </style>
</head>

@if(session('success'))
<div id="notification" class="fixed top-4 right-4 z-50 bg-white rounded-xl shadow-lg border border-green-200 p-4 flex items-center gap-3 animate-slide-in">
    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
        <iconify-icon icon="mdi:check-circle" class="text-2xl text-green-600"></iconify-icon>
    </div>
    <div>
        <p class="font-semibold text-gray-800 text-sm">Berhasil!</p>
        <p class="text-xs text-gray-600">{{ session('success') }}</p>
    </div>
    <button onclick="closeNotification()" class="ml-4 text-gray-400 hover:text-gray-600">
        <iconify-icon icon="mdi:close" class="text-xl"></iconify-icon>
    </button>
</div>

<script>
    function closeNotification() {
        const notif = document.getElementById('notification');
        if (notif) {
            notif.classList.add('animate-slide-out');
            setTimeout(() => notif.remove(), 300);
        }
    }

    setTimeout(() => {
        closeNotification();
    }, 5000);
</script>

<style>
    @keyframes slide-in {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slide-out {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }

    .animate-slide-in {
        animation: slide-in 0.3s ease-out;
    }

    .animate-slide-out {
        animation: slide-out 0.3s ease-in;
    }
</style>
@endif

@if(session('error'))
<div id="notification" class="fixed top-4 right-4 z-50 bg-white rounded-xl shadow-lg border border-red-200 p-4 flex items-center gap-3 animate-slide-in">
    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
        <iconify-icon icon="mdi:alert-circle" class="text-2xl text-red-600"></iconify-icon>
    </div>
    <div>
        <p class="font-semibold text-gray-800 text-sm">Gagal!</p>
        <p class="text-xs text-gray-600">{{ session('error') }}</p>
    </div>
    <button onclick="closeNotification()" class="ml-4 text-gray-400 hover:text-gray-600">
        <iconify-icon icon="mdi:close" class="text-xl"></iconify-icon>
    </button>
</div>
@endif

<body class="text-slate-800 flex min-h-screen relative overflow-x-hidden">

  <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden transition-opacity duration-300"></div>

  <aside id="sidebar" class="fixed lg:sticky top-0 left-0 w-64 bg-slate-900 text-white h-screen z-50 flex flex-col shadow-2xl transition-transform duration-300 -translate-x-full lg:translate-x-0">
    <div class="p-6 border-b border-slate-800 flex items-center justify-between gap-3">
        <img src="{{ asset('assets/images/logo_admin.png') }}" class="h-15 w-auto" alt="Logo">
        <button id="closeSidebar" class="lg:hidden text-slate-400 hover:text-white transition">
            <iconify-icon icon="mdi:close" class="text-2xl"></iconify-icon>
        </button>
    </div>

    <nav class="flex-1 mt-6 px-4 space-y-2 sidebar-scroll overflow-y-auto">
      <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 p-3.5 rounded-xl transition-all {{ request()->is('admin') ? 'bg-[var(--primary)] text-white shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
        <iconify-icon icon="mdi:view-dashboard" class="text-xl"></iconify-icon>
        <span class="text-sm font-semibold">Dashboard</span>
      </a>

      <a href="{{ route('obat.index') }}" class="flex items-center gap-3 p-3.5 rounded-xl transition-all {{ request()->is('admin/obat*') ? 'bg-[var(--primary)] text-white shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
        <iconify-icon icon="mdi:pill" class="text-xl"></iconify-icon>
        <span class="text-sm font-semibold">Produk</span>
      </a>

      <a href="{{ route('admin.pesanan.index') }}" class="flex items-center gap-3 p-3.5 rounded-xl transition-all {{ request()->is('admin/pesanan*') ? 'bg-[var(--primary)] text-white shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
        <iconify-icon icon="mdi:cart-check" class="text-xl"></iconify-icon>
        <span class="text-sm font-semibold">Pemesanan</span>
      </a>
    </nav>

    <div class="p-4 border-t border-slate-800">
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="w-full flex items-center gap-3 p-3 text-red-400 hover:bg-red-500/10 rounded-xl transition-all text-sm font-bold">
            <iconify-icon icon="mdi:logout" class="text-xl"></iconify-icon>
            <span>KELUAR</span>
          </button>
        </form>
    </div>
  </aside>

  <div class="flex-1 flex flex-col min-w-0">
    <header class="h-16 bg-white border-b border-gray-100 flex items-center px-4 lg:px-8 justify-between sticky top-0 z-40 shadow-sm">
        <div class="flex items-center gap-4">
            <button id="openSidebar" class="lg:hidden flex items-center justify-center w-10 h-10 rounded-xl bg-white shadow-sm border border-gray-200 hover:shadow-md transition">
                <iconify-icon icon="mdi:menu" class="text-2xl text-gray-600"></iconify-icon>
            </button>
        </div>

        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <p class="text-xs text-gray-500">Administrator</p>
                <p class="text-sm font-bold text-gray-800">{{ auth()->guard('api')->user()->username ?? 'Admin' }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center border border-gray-200">
                <iconify-icon icon="mdi:account" class="text-gray-600 text-xl"></iconify-icon>
            </div>
        </div>
    </header>

    <main class="p-4 lg:p-8 no-scrollbar overflow-y-auto">
      @yield('content')
    </main>
  </div>

  <script>
    const sidebar = document.getElementById('sidebar');
    const openSidebarBtn = document.getElementById('openSidebar');
    const closeSidebarBtn = document.getElementById('closeSidebar');
    const overlay = document.getElementById('sidebarOverlay');

    function toggleSidebar() {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden');
    }

    openSidebarBtn.addEventListener('click', toggleSidebar);
    closeSidebarBtn.addEventListener('click', toggleSidebar);
    overlay.addEventListener('click', toggleSidebar);
  </script>

</body>
</html>
