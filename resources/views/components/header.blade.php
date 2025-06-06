    <header class="bg-white shadow-lg border-b-4 border-blue-600">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-600 p-2 rounded-lg">
                        <i class="fas fa-car text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ env('APP_NAME', 'Sistem Parkir Kampus') }}</h1>
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
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Logout</button>
                        </form>
                    @else
                        {{-- <a class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200 shadow-md" href="{{ route('login') }}"><i class="fas fa-sign-in-alt mr-2"></i> Login</a> --}}
                        <button onclick="showLoginModal()"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200 shadow-md">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </header>
