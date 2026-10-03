<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun – Apotek Alfina Rizqy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #A80505; --primary-dark: #6C0000; }
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }

        @keyframes slide-in {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }
    </style>
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

<body class="flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md transform-gpu">
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-block">
                <img src="{{ asset('assets/images/logo_apotek.png') }}" class="h-16 w-auto mx-auto mb-4" alt="Logo">
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Buat Akun Baru</h1>
            <p class="text-gray-500 text-sm">Lengkapi data di bawah untuk bergabung</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 p-8">
            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 rounded-xl border @error('name') border-red-500 @else border-gray-200 @enderror focus:border-[var(--primary)] focus:ring-1 focus:ring-[var(--primary)] outline-none transition-all bg-gray-50 focus:bg-white text-sm"
                        placeholder="Contoh: Budi Santoso">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <iconify-icon icon="mdi:alert-circle" class="text-sm"></iconify-icon>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 rounded-xl border @error('email') border-red-500 @else border-gray-200 @enderror focus:border-[var(--primary)] focus:ring-1 focus:ring-[var(--primary)] outline-none transition-all bg-gray-50 focus:bg-white text-sm"
                        placeholder="nama@email.com">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <iconify-icon icon="mdi:alert-circle" class="text-sm"></iconify-icon>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Kata Sandi</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 rounded-xl border @error('password') border-red-500 @else border-gray-200 @enderror focus:border-[var(--primary)] focus:ring-1 focus:ring-[var(--primary)] outline-none transition-all bg-gray-50 focus:bg-white text-sm"
                        placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <iconify-icon icon="mdi:alert-circle" class="text-sm"></iconify-icon>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-start gap-2 pt-2">
                    <input type="checkbox" required class="mt-1 accent-[var(--primary)]">
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Saya menyetujui <a href="#" class="text-[var(--primary)] underline">Syarat & Ketentuan</a> serta Kebijakan Privasi yang berlaku.
                    </p>
                </div>

                <button type="submit"
                        class="w-full bg-[var(--primary)] hover:bg-[var(--primary-dark)] text-white font-semibold py-3 rounded-xl shadow-lg shadow-red-200 transition-all active:scale-[0.98] transform-gpu">
                    Daftar Sekarang
                </button>
            </form>

            <div class="mt-8 text-center border-t pt-6">
                <p class="text-sm text-gray-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-bold text-[var(--primary)] hover:underline">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>

</body>

<script>
    function closeNotification() {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.style.animation = 'slide-in 0.3s ease-out reverse';
            setTimeout(() => notification.remove(), 300);
        }
    }

    // Auto hide setelah 5 detik
    setTimeout(() => {
        const notification = document.getElementById('notification');
        if (notification) closeNotification();
    }, 5000);
</script>

</html>
