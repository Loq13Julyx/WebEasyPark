<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Easy Park Mall — Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col md:flex-row">
    <!-- Left section -->
    <div
        class="w-full md:w-[55%] min-h-[60vh] md:min-h-screen relative flex flex-col justify-center items-center md:items-start px-6 md:px-16 py-16 md:py-20 text-white overflow-hidden">

        <!-- Background image -->
        <img src="{{ asset('images/parkir.png') }}" alt="Parkir Mall"
            class="absolute inset-0 w-full h-full object-cover" />

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>

        <!-- Content -->
        <img src="{{ asset('images/logo-parkir.png') }}" alt="Easy Park Mall logo"
            class="w-28 md:w-36 mb-6 z-10 relative" />
        <h1
            class="text-3xl md:text-4xl font-extrabold mb-3 md:mb-4 drop-shadow-lg max-w-md text-center md:text-left z-10 relative">
            Selamat Datang di Easy Park Mall
        </h1>
        <p
            class="text-base md:text-lg mb-6 md:mb-8 max-w-md leading-relaxed drop-shadow-md text-center md:text-left z-10 relative">
            Solusi parkir cerdas untuk mall anda — kelola kendaraan dengan sistem otomatis yang cepat, aman, dan
            efisien.
        </p>
        <button
            class="bg-[#0086FF] text-white font-semibold rounded-full py-2.5 md:py-3 px-8 md:px-10 shadow-lg hover:bg-[#0065d1] transition-colors drop-shadow-md z-10 relative text-sm md:text-base">
            Pelajari Lebih Lanjut
        </button>
    </div>

    <!-- Right section: Form Login -->
    <div
        class="w-full md:w-[45%] min-h-[40vh] md:min-h-screen flex flex-col justify-center items-center px-6 md:px-16 py-10 md:py-0 bg-white">
        <h1 class="text-[#222222] font-bold text-xl md:text-2xl mb-1 md:mb-2 text-center">Halo, Selamat Datang!</h1>
        <p class="text-[#555555] text-sm mb-6 md:mb-8 max-w-xs text-center">
            Masuk untuk mengelola sistem parkir mall anda menggunakan Easy Park.
        </p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Error Message -->
        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded text-sm text-center">
                {{ session('error') }}
            </div>
        @endif

        <form class="w-full max-w-xs flex flex-col gap-4" method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="relative">
                <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                <input name="email" type="email" placeholder="Alamat Email" value="{{ old('email') }}" required
                    autofocus
                    class="w-full border border-gray-300 rounded-full py-3 pl-11 pr-4 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#0086FF]" />
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-600 text-xs" />
            </div>

            <!-- Password -->
            <div class="relative">
                <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                <input id="password" name="password" type="password" placeholder="Kata Sandi" required
                    class="w-full border border-gray-300 rounded-full py-3 pl-11 pr-10 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#0086FF]" />
                <!-- Eye Icon -->
                <button type="button" onclick="togglePassword()"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 focus:outline-none">
                    <i id="eyeIcon" class="fas fa-eye"></i>
                </button>

                <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-600 text-xs" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-2 text-sm text-gray-600">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                    <span class="ml-2">Ingat saya</span>
                </label>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="bg-[#0086FF] text-white rounded-full py-3 text-center text-sm font-semibold hover:bg-[#0072e6] transition-colors">
                Masuk ke Sistem
            </button>

        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const eyeIcon = document.getElementById("eyeIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>

</html>
