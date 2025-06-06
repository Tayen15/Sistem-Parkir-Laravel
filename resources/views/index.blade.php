@extends('layouts.app')

@section('title', 'Selamat Datang di')

@section('content')
    <!-- Hero Section -->
    <div class="text-center mb-12">
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
            <div class="flex justify-center mb-6">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-4 rounded-full shadow-lg">
                    <i class="fas fa-parking text-white text-4xl"></i>
                </div>
            </div>
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Selamat Datang di {{ env('APP_NAME', 'Sistem Parkir Kampus') }}</h2>
            <p class="text-xl text-gray-600 mb-6">Solusi parkir cerdas untuk seluruh masyarakat kampus</p>
            <div class="flex justify-center space-x-8 text-sm text-gray-600">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-clock text-blue-600"></i>
                    <span>24/7 Available</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-shield-alt text-green-600"></i>
                    <span>Secure Parking</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-mobile-alt text-purple-600"></i>
                    <span>Digital Payment</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Ketersediaan Parkir -->
    <div class="mb-12">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Status Ketersediaan Parkir</h3>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach ($areaParkirs as $area)
                <div
                    class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 hover:shadow-xl transition duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-xl font-bold text-gray-800">{{ $area->name }}</h4>
                        <div
                            class="bg-{{ $area->available_slots > 0 ? ($area->available_slots / $area->kapasitas > 0.2 ? 'green' : 'yellow') : 'red' }}-100 p-3 rounded-xl">
                            <i
                                class="fas fa-car text-{{ $area->available_slots > 0 ? ($area->available_slots / $area->kapasitas > 0.2 ? 'green' : 'yellow') : 'red' }}-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tersedia</span>
                            <span
                                class="font-bold text-{{ $area->available_slots > 0 ? ($area->available_slots / $area->kapasitas > 0.2 ? 'green' : 'yellow') : 'red' }}-600 text-lg">{{ $area->available_slots }}
                                slot</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total</span>
                            <span class="font-bold text-gray-800">{{ $area->kapasitas }} slot</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-{{ $area->available_slots > 0 ? ($area->available_slots / $area->kapasitas > 0.2 ? 'green' : 'yellow') : 'red' }}-500 h-3 rounded-full shadow-inner"
                                style="width: {{ ($area->available_slots / $area->kapasitas) * 100 }}%"></div>
                        </div>
                        <p
                            class="text-sm text-{{ $area->available_slots > 0 ? ($area->available_slots / $area->kapasitas > 0.2 ? 'green' : 'yellow') : 'red' }}-600 font-medium">
                            {{ $area->available_slots > 0 ? ($area->available_slots / $area->kapasitas > 0.2 ? '✓ Tersedia' : '⚠ Terbatas') : '✗ Penuh' }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Form Registrasi Kendaraan -->
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white">
                <div class="flex items-center justify-center mb-4">
                    <div class="bg-white/20 p-3 rounded-lg">
                        <i class="fas fa-car-side text-3xl"></i>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-center mb-2">Daftarkan Kendaraan Anda</h2>
                <p class="text-center text-blue-100">Mulai parkir dengan mudah dan aman</p>
            </div>
            <form action="{{ route('parking.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <input type="hidden" name="selected_area" id="selected_area" value="{{ old('selected_area') }}">

                {{-- Tampilan Error Umum (opsional, jika ingin menampilkan semua error di satu tempat juga) --}}
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <strong class="font-bold">Oops!</strong>
                        <span class="block sm:inline">Ada beberapa masalah dengan input Anda:</span>
                        <ul class="mt-3 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Informasi Pengguna</h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"><i
                                    class="fas fa-user mr-2 text-blue-600"></i> Nama Lengkap</label>
                            <input type="text" name="name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('name') border-red-500 @enderror"
                                placeholder="Masukkan nama lengkap" required value="{{ old('name') }}">
                            @error('name')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"><i
                                    class="fas fa-envelope mr-2 text-blue-600"></i> Email</label>
                            <input type="email" name="email"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('email') border-red-500 @enderror"
                                placeholder="Masukkan email" required value="{{ old('email') }}">
                            @error('email')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Informasi Kendaraan</h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"><i
                                    class="fas fa-car mr-2 text-blue-600"></i> Jenis Kendaraan</label>
                            <select name="vehicle_type_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 capitalize focus:ring-blue-500 focus:border-transparent transition duration-200 @error('vehicle_type_id') border-red-500 @enderror"
                                required onchange="updateTariff(this.value)">
                                <option value="">Pilih Jenis Kendaraan</option>
                                @foreach ($vehicleTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('vehicle_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vehicle_type_id')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"><i
                                    class="fas fa-certificate mr-2 text-blue-600"></i> Nomor Polisi</label>
                            <input type="text" name="nopol"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('nopol') border-red-500 @enderror"
                                placeholder="Contoh: B 1234 ABC" required style="text-transform: uppercase"
                                value="{{ old('nopol') }}">
                            @error('nopol')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"><i
                                    class="fas fa-industry mr-2 text-blue-600"></i> Merk Kendaraan</label>
                            <input type="text" name="merk"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('merk') border-red-500 @enderror"
                                placeholder="Contoh: Honda, Toyota" required value="{{ old('merk') }}">
                            @error('merk')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"><i
                                    class="fas fa-palette mr-2 text-blue-600"></i> Tahun Beli</label>
                            <input type="number" name="thn_beli"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('thn_beli') border-red-500 @enderror"
                                placeholder="Contoh: 2020" required min="1990" max="{{ date('Y') }}"
                                value="{{ old('thn_beli') }}">
                            @error('thn_beli')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"><i
                                class="fas fa-palette mr-2 text-blue-600"></i> Deskripsi Kendaraan</label>
                        <input type="text" name="deskripsi"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('deskripsi') border-red-500 @enderror"
                            placeholder="Contoh: Cat warna Hitam, Putih" value="{{ old('deskripsi') }}">
                        {{-- Hapus 'required' jika di controller nullable --}}
                        @error('deskripsi')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Pilih Area Parkir</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i> Area Parkir
                        </label>
                        <select name="selected_area" id="selected_area"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('selected_area') border-red-500 @enderror"
                            required>
                            <option value="">Pilih Area Parkir</option>
                            @foreach ($areaParkirs as $area)
                                <option value="{{ $area->id }}"
                                    {{ old('selected_area') == $area->id ? 'selected' : '' }}
                                    {{ $area->available_slots == 0 ? 'disabled' : '' }}
                                    class="text-{{ $area->available_slots > 0 ? ($area->available_slots / $area->kapasitas > 0.2 ? 'green' : 'yellow') : 'red' }}-600">
                                    {{ $area->name }} - Dekat {{ $area->keterangan }}
                                    {{ $area->available_slots }}/{{ $area->kapasitas }} slot tersedia
                                </option>
                            @endforeach
                        </select>
                        @error('selected_area')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-800 mb-4">Informasi Tarif</h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-blue-700 mb-2">Tarif per Jam:</p>
                            <p class="text-2xl font-bold text-blue-800" id="tariff">Pilih jenis kendaraan</p>
                        </div>
                        <div>
                            <p class="text-sm text-blue-700 mb-2">Metode Pembayaran:</p>
                            <p class="text-lg font-semibold text-blue-800">Bayar saat keluar</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="flex items-start space-x-3 cursor-pointer">
                        <input type="checkbox" name="terms"
                            class="mt-1 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 @error('terms') border-red-500 @enderror"
                            required>
                        <span class="text-sm text-gray-700">Saya menyetujui <a href="#"
                                class="text-blue-600 hover:underline">syarat dan ketentuan</a> yang berlaku dan bertanggung
                            jawab atas keamanan kendaraan saya.</span>
                    </label>
                    @error('terms')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-4 px-6 rounded-xl transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-car mr-2"></i> Mulai Parkir Sekarang
                </button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let selectedArea = null;

        function updateTariff(vehicleTypeId) {
            const tariffElement = document.getElementById("tariff");
            const vehicleType = @json($vehicleTypes->keyBy('id')->toArray())[vehicleTypeId];
            tariffElement.textContent = vehicleType ? `Rp ${vehicleType.fee.toLocaleString()}` : 'Pilih jenis kendaraan';
        }

        function selectArea(areaId, element) {
            selectedArea = areaId;
            document.getElementById('selected_area').value = areaId;
            document.querySelectorAll(".border-2").forEach((el) => {
                if (!el.classList.contains("cursor-not-allowed")) {
                    el.classList.remove("border-blue-500", "bg-blue-50");
                    el.classList.add("border-gray-200");
                    const radio = el.querySelector(".rounded-full");
                    if (radio) { // Tambahkan pengecekan ini
                        radio.classList.remove("bg-blue-500", "border-blue-500");
                        radio.classList.add("border-gray-300");
                    }
                }
            });
            if (!element.classList.contains("cursor-not-allowed")) {
                element.classList.remove("border-gray-200");
                element.classList.add("border-blue-500", "bg-blue-50");
                const radio = element.querySelector(".rounded-full");
                if (radio) { // Tambahkan pengecekan ini
                    radio.classList.remove("border-gray-300");
                    radio.classList.add("bg-blue-500", "border-blue-500");
                }
            }
        }
    </script>
@endsection
