<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem Parkir Kampus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header untuk Authenticated User -->
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
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Selamat datang,</p>
                        <p class="font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Logout</button>
                    </form>
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
</body>

</html>
