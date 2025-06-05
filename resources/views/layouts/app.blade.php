<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem Parkir Kampus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e40af',
                        secondary: '#64748b'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header -->
    <header class="bg-white shadow-lg border-b-4 border-blue-600">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-600 p-2 rounded-lg">
                        <i class="fas fa-car text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Sistem Parkir Kampus</h1>
                        <p class="text-sm text-gray-600">Universitas Indonesia</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Selamat datang,</p>
                            <p class="font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <a href="{{ route('logout') }}"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Logout</a>
                    @else
                        <button onclick="showLoginModal()"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200 shadow-md">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Content -->
    <div class="container mx-auto px-4 py-8">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="text-center">
                <p class="text-gray-300">© 2025 Sistem Parkir Kampus - Universitas Indonesia</p>
                <p class="text-sm text-gray-400 mt-2">Untuk bantuan hubungi: parkir@ui.ac.id</p>
            </div>
        </div>
    </footer>

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

    <!-- Modal Register -->
    <div id="registerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 m-4 w-full max-w-md">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Register</h2>
                <button onclick="closeRegisterModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
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
                    Register
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

        function showRegisterModal() {
            document.getElementById("registerModal").classList.remove("hidden");
            document.getElementById("registerModal").classList.add("flex");
            document.body.style.overflow = "hidden";
        }

        function closeRegisterModal() {
            document.getElementById("registerModal").classList.add("hidden");
            document.getElementById("registerModal").classList.remove("flex");
            document.body.style.overflow = "auto";
        }

        document.getElementById("loginModal").addEventListener("click", function(e) {
            if (e.target === this) closeLoginModal();
        });

        document.getElementById("registerModal").addEventListener("click", function(e) {
            if (e.target === this) closeRegisterModal();
        });
    </script>
    @yield('scripts')
</body>

</html>
