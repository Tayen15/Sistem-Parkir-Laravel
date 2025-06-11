<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Definisi Variabel Default --}}
    @php
        $defaultBaseUrl = url('/'); 
        $defaultDescription =
            'Sistem manajemen parkir kampus yang efisien dan modern untuk memudahkan pengelolaan parkir di lingkungan kampus.';
        $defaultKeywords =
            'sistem parkir, parkir kampus, manajemen parkir, smart parking, kampus, manajemen kendaraan, efisien, modern';
        $defaultAuthorName = config('app.name', 'Sistem Parkir Kampus'); 
        $defaultOgImage = 'https://cdn.oktaa.my.id/og-banner.png'; 
        $defaultFavicon = '/favicon.ico'; 
        $defaultAppleTouchIcon = 'https://cdn.oktaa.my.id/apple-touch-icon.png'; 

        $pageTitle = View::yieldContent('title')
            ? View::yieldContent('title') . ' - ' . config('app.name')
            : config('app.name', 'Sistem Parkir Kampus');
        $pageDescription = View::yieldContent('description') ?: $defaultDescription;
    @endphp

    {{-- SEO & Metadata Utama --}}
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="keywords" content="@yield('keywords', $defaultKeywords)">
    <meta name="author" content="@yield('author', $defaultAuthorName)">
    <meta name="robots" content="@yield('robots', 'index, follow')"> 

    {{-- Canonical URL --}}
    <link rel="canonical" href="@yield('canonical_url', $defaultBaseUrl)">

    {{-- Favicon & Icons --}}
    <link rel="icon" href="@yield('icon', $defaultFavicon)" type="image/x-icon">
    <link rel="apple-touch-icon" href="@yield('apple_icon', $defaultAppleTouchIcon)">

    {{-- Open Graph (Facebook, LinkedIn, dll.) --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('og_title', $pageTitle)">
    <meta property="og:description" content="@yield('og_description', $pageDescription)">
    <meta property="og:url" content="@yield('og_url', $defaultBaseUrl)">
    <meta property="og:site_name" content="@yield('og_site_name', config('app.name', 'Sistem Parkir Kampus'))">
    <meta property="og:image" content="@yield('og_image', $defaultOgImage)">
    <meta property="og:image:width" content="@yield('og_image_width', 1200)">
    <meta property="og:image:height" content="@yield('og_image_height', 630)">
    <meta property="og:image:alt" content="@yield('og_image_alt', $pageTitle . ' Banner')">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:title" content="@yield('twitter_title', $pageTitle)">
    <meta name="twitter:description" content="@yield('twitter_description', $pageDescription)">
    <meta name="twitter:image" content="@yield('twitter_image', $defaultOgImage)"> 

    {{-- CSRF Token (Sudah ada, tapi pastikan posisinya) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Stylesheets & Scripts --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
