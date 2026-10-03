<nav id="navbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-white border-b border-gray-100">
  <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-4">

    <a href="{{ url('/') }}" class="shrink-0">
      <div class="flex items-center gap-2">
        <img src="{{ asset('assets/images/logo_apotek.png') }}" class="h-[40px] md:h-[50px] w-auto object-contain" alt="Logo">
      </div>
    </a>

    <!-- Form Pencarian Desktop -->
    <div class="hidden md:flex flex-1 px-6 justify-center">
      <form action="{{ route('search') }}" method="GET" class="relative w-full max-w-[500px]">
        <iconify-icon
          icon="mdi:magnify"
          class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xl pointer-events-none">
        </iconify-icon>

        <input
          type="text"
          name="q"
          placeholder="Cari produk..."
          class="w-full border rounded-lg py-2 pl-10 pr-3 text-sm
                 outline outline-1 outline-gray-400 focus:outline-[var(--primary)] bg-white">
      </form>
    </div>

    <div class="flex items-center gap-2 md:gap-4">
      @php
          $user = auth()->guard('api')->user();
          $cartCount = $user ? \App\Models\Keranjang::where('user_id', $user->id)->sum('jumlah') : 0;
      @endphp

      <!-- Button Search Mobile -->
      <a href="{{ route('search') }}" class="md:hidden p-2 text-gray-600 hover:text-[var(--primary)] transition">
        <iconify-icon icon="mdi:magnify" class="text-2xl"></iconify-icon>
      </a>

      @if(!$user)
          <a href="{{ route('login') }}" class="px-4 py-2 bg-[var(--primary)] text-white rounded-md shadow hover:bg-[var(--primary-dark)] transition text-sm whitespace-nowrap">
            Login
          </a>
      @elseif($user->role === 'admin')
          <div class="flex items-center gap-3 md:gap-4">
              <a href="{{ url('/admin') }}" class="flex items-center gap-1 text-sm font-bold text-blue-600 hover:text-blue-800 transition">
                  <iconify-icon icon="mdi:view-dashboard" class="text-xl md:text-2xl"></iconify-icon>
                  <span class="hidden md:inline">Dashboard Admin</span>
              </a>

              <div class="h-6 w-px bg-gray-200"></div>

              <form action="{{ route('logout') }}" method="POST" class="flex items-center">
                  @csrf
                  <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-full transition flex items-center gap-1">
                      <iconify-icon icon="mdi:logout" class="text-xl md:text-2xl"></iconify-icon>
                      <span class="hidden md:inline text-xs font-bold uppercase">Logout</span>
                  </button>
              </form>
          </div>
      @else
          <div class="flex items-center gap-2 md:gap-3">

              <a href="{{ url('/keranjang') }}" class="relative p-2 text-gray-600 hover:text-[var(--primary)] transition flex items-center">
                  <iconify-icon icon="mdi:cart-outline" class="text-2xl md:text-2xl"></iconify-icon>
                  @if($cartCount > 0)
                  <span class="absolute top-0 right-0 md:right-auto md:left-5 bg-[var(--primary)] text-white text-[10px] px-1.5 py-0.5 rounded-full min-w-[18px] text-center">
                    {{ $cartCount }}
                  </span>
                  @endif
              </a>

              <a href="{{ url('/pesanan') }}" class="p-2 text-gray-600 hover:text-[var(--primary)] transition flex items-center" title="Riwayat Pesanan">
                  <iconify-icon icon="mdi:history" class="text-2xl md:text-2xl"></iconify-icon>
              </a>

              <div class="h-6 w-px bg-gray-200"></div>

              <div class="flex items-center gap-2">
                  <div class="text-right hidden md:block">
                      <p class="text-xs font-bold leading-none">{{ $user->username }}</p>
                      <p class="text-[10px] text-gray-500">Pelanggan</p>
                  </div>
                  <form action="{{ route('logout') }}" method="POST" class="flex items-center">
                      @csrf
                      <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-red-50 hover:text-red-600 transition shadow-sm">
                          <iconify-icon icon="mdi:logout" class="text-xl"></iconify-icon>
                      </button>
                  </form>
              </div>
          </div>
      @endif
    </div>

  </div>
</nav>
