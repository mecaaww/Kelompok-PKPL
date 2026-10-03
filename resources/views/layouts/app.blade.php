<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Apotek Alfina Rizqy')</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary: #A80505;
      --primary-dark: #6C0000;
      --soft-bg: #F8FAFC;
    }
    body { font-family: 'Poppins', sans-serif; }
    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }
    .no-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
    #navbar, .product-card { transform: translateZ(0); }
    .clamp-12 {
      display: -webkit-box;
      -webkit-line-clamp: 12;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
  </style>

  <script src="{{ asset('/js/index.js') }}" defer></script>
  <script src="{{ asset('js/detail_product.js') }}" defer></script>
</head>

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

<body class="text-slate-800">

  @include('partials.navbar')

  <div class="h-20"></div>

  <main>
    @yield('content')
  </main>

  @include('partials.footer')

</body>
</html>
