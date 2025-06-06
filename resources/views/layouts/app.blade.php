<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ env('APP_NAME', 'Sistem Parkir Kampus') }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistem manajemen parkir kampus yang efisien dan modern untuk memudahkan pengelolaan parkir di lingkungan kampus">
    <meta name="keywords" content="sistem parkir, parkir kampus, manajemen parkir, smart parking, kampus, firtiansyah okta website">
    <meta name="author" content="{{ env('APP_NAME', 'Sistem Parkir Kampus') }}">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="@yield('title') - {{ env('APP_NAME', 'Sistem Parkir Kampus') }}">
    <meta property="og:description" content="Sistem manajemen parkir kampus yang efisien dan modern">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header -->

    @include('components.header')

    <!-- Content -->
    <div class="container mx-auto px-4 py-8">
        @yield('content')
    </div>

    <!-- Footer -->
    @include('components.footer')

    <!-- Modal Login -->
    <div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 m-4 w-full max-w-md">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Login</h2>
                <button onclick="closeLoginModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Masukkan email" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Masukkan password" required>
                </div>
                @if ($errors->any())
                    <div class="text-red-500 text-sm">{{ $errors->first() }}</div>
                @endif
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                    Login
                </button>
            </form>
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">Belum punya akun? Daftar langsung saat parkir pertama kali</p>
            </div>
        </div>
    </div>

    <script>
        function showLoginModal() {
            document.getElementById("loginModal").classList.remove("hidden");
            document.getElementById("loginModal").classList.add("flex");
            document.body.style.overflow = "hidden";
        }

        function closeLoginModal() {
            document.getElementById("loginModal").classList.add("hidden");
            document.getElementById("loginModal").classList.remove("flex");
            document.body.style.overflow = "auto";
        }

        document.getElementById("loginModal").addEventListener("click", function(e) {
            if (e.target === this) closeLoginModal();
        });


    </script>
    @yield('scripts')
</body>

</html>
