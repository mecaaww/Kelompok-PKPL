<footer class="bg-[var(--soft-bg)] border-t border-gray-200 mt-10">
  <div class="max-w-6xl mx-auto px-4 py-10 grid grid-cols-1 md:grid-cols-3 gap-10 text-sm text-gray-600">
    <div class="space-y-3">
      <img src="{{ asset('assets/images/logo_apotek.png') }}" class="h-12 object-contain">
      <p>Apotek Alfina Rizqy menyediakan berbagai obat, vitamin, dan alat kesehatan.</p>
      <p class="text-gray-500 text-xs mt-4">© 2025 Apotek Alfina Rizqy. All rights reserved.</p>
    </div>
    <div class="grid grid-cols-2 gap-8">
      <div>
        <h3 class="font-semibold text-gray-800 mb-3">Menu</h3>
        <ul class="space-y-2">
          <li><a href="{{ url('/') }}" class="hover:text-[var(--primary)]">Beranda</a></li>
          <li><a href="#" class="hover:text-[var(--primary)]">Produk</a></li>
        </ul>
      </div>
      <div>
        <h3 class="font-semibold text-gray-800 mb-3">Bantuan</h3>
        <ul class="space-y-2 text-sm">
          <li><a href="#" class="hover:text-[var(--primary)]">FAQ</a></li>
          <li><a href="#" class="hover:text-[var(--primary)]">Kebijakan Privasi</a></li>
        </ul>
      </div>
    </div>
    <div class="space-y-3 text-sm">
      <h3 class="font-semibold text-gray-800 mb-3 font-bold uppercase tracking-tighter">Kontak Kami</h3>
      <p class="flex items-center gap-2 font-bold"><iconify-icon icon="mdi:whatsapp"></iconify-icon> +62 851-7238-2846</p>
      <p class="flex items-center gap-2 font-bold"><iconify-icon icon="mdi:email-outline"></iconify-icon> alfinarizqy@gmail.com</p>
    </div>
  </div>
</footer>
