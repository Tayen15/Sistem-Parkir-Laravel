@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
    <!-- Status Parkir Saat Ini -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">Status Parkir Aktif</h2>
                        <p class="text-blue-100">Kendaraan Anda sedang parkir</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <i class="fas fa-car text-3xl"></i>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="bg-blue-100 p-2 rounded-lg">
                                <i class="fas fa-car text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Nomor Polisi</p>
                                <p class="font-bold text-lg">
                                    {{ $activeParking->vehicle->nopol ?? 'Tidak ada parkir aktif' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="bg-green-100 p-2 rounded-lg">
                                <i class="fas fa-map-marker-alt text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Lokasi Parkir</p>
                                <p class="font-bold text-lg">
                                    {{ $activeParking->areaParkir->name ?? 'Tidak ada parkir aktif' }} - Slot
                                    {{ $activeParking->keterangan ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="bg-purple-100 p-2 rounded-lg">
                                <i class="fas fa-clock text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Waktu Masuk</p>
                                <p class="font-bold text-lg">{{ $activeParking->start ?? 'N/A' }} WIB</p>
                                <p class="text-sm text-gray-500">{{ $activeParking->tanggal ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600">Durasi Parkir</span>
                                <span class="text-2xl font-bold text-blue-600"
                                    id="duration">{{ $activeParking ? $this->calculateDuration($activeParking->start) : '0j 0m' }}</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600">Tarif per Jam</span>
                                <span class="font-semibold">Rp
                                    {{ $activeParking->vehicle->vehicleType->name === 'motor' ? '2.000' : '3.000' }}</span>
                            </div>
                            <div class="border-t pt-2 mt-2">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold">Estimasi Biaya</span>
                                    <span class="text-xl font-bold text-red-600">Rp
                                        {{ $activeParking ? $this->calculateFee($activeParking->start, now()->toTimeString(), $activeParking->vehicle->vehicleType->name) : '0' }}</span>
                                </div>
                            </div>
                        </div>
                        @if ($activeParking)
                            <form action="{{ route('parking.exit', $activeParking->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center space-x-2">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Keluar Parkir</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Ketersediaan Parkir -->
    <div class="grid md:grid-cols-3 gap-6 mb-8">
        @foreach ($areaParkirs as $area)
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">{{ $area->name }}</h3>
                    <div
                        class="bg-{{ $area->available_slots > 0 ? ($area->available_slots / $area->kapasitas > 0.2 ? 'green' : 'yellow') : 'red' }}-100 p-2 rounded-lg">
                        <i
                            class="fas fa-car text-{{ $area->available_slots > 0 ? ($area->available_slots / $area->kapasitas > 0.2 ? 'green' : 'yellow') : 'red' }}-600"></i>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tersedia</span>
                        <span
                            class="font-bold text-{{ $area->available_slots > 0 ? ($area->available_slots / $area->kapasitas > 0.2 ? 'green' : 'yellow') : 'red' }}-600">{{ $area->available_slots }}
                            slot</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total</span>
                        <span class="font-bold">{{ $area->kapasitas }} slot</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-{{ $area->available_slots > 0 ? ($area->available_slots / $area->kapasitas > 0.2 ? 'green' : 'yellow') : 'red' }}-600 h-2 rounded-full"
                            style="width: {{ ($area->available_slots / $area->kapasitas) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Riwayat Parkir -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800 flex items-center space-x-2">
                <i class="fas fa-history text-blue-600"></i>
                <span>Riwayat Parkir</span>
            </h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach ($pastParkings as $parking)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border">
                        <div class="flex items-center space-x-4">
                            <div class="bg-blue-100 p-2 rounded-lg">
                                <i class="fas fa-car text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold">{{ $parking->vehicle->nopol }}</p>
                                <p class="text-sm text-gray-600">{{ $parking->areaParkir->name }} - Slot
                                    {{ $parking->keterangan }}</p>
                                <p class="text-xs text-gray-500">{{ $parking->tanggal }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Durasi:
                                {{ $this->calculateDuration($parking->start, $parking->end) }}</p>
                            <p class="font-bold text-green-600">Rp {{ number_format($parking->biaya, 0, ',', '.') }}</p>
                            <span
                                class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Selesai</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6 text-center">
                <a href="{{ route('parking.history') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200">Lihat
                    Semua Riwayat</a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function updateDuration(startTime) {
            const now = new Date();
            const start = new Date(now.toDateString() + ' ' + startTime + ' UTC');
            const diff = now - start;
            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            document.getElementById('duration').textContent = `${hours}j ${minutes}m`;
        }

        document.addEventListener('DOMContentLoaded', () => {
            @if ($activeParking)
                setInterval(() => updateDuration('{{ $activeParking->start }}'), 60000);
            @endif
        });
    </script>
@endsection
