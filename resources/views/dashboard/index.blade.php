@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
    @if (session('registration_success'))
        <div class="mb-8 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <p class="font-semibold">Registrasi Berhasil!</p>
            <p>Berikut adalah kredensial login Anda:</p>
            <p><strong>Email:</strong> {{ session('registration_success.email') }}</p>
            <p><strong>Password:</strong> {{ session('registration_success.password') }}</p>
            <p class="text-sm mt-2">Silakan simpan kredensial ini dan ganti password Anda di halaman profil.</p>
        </div>
    @endif

    @if (session('success_message'))
        <div class="mb-8 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <p class="font-semibold">Update password Berhasil!</p>
        </div>
    @endif

    @if ($needsPasswordChange)
        <div class="mb-8 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg">
            <p class="font-semibold">Peringatan!</p>
            <p>Untuk keamanan, silakan ganti password Anda segera.</p>
            <button id="openChangePasswordModal" class="mt-2 text-blue-600 hover:underline focus:outline-none">Ganti Password
                Sekarang</button>
        </div>
    @endif

    <div id="changePasswordModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center z-50 p-4 hidden">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full">
            <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Ganti Password Anda</h3>

            @if ($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Gagal mengganti password!</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @error('current_password')
                            <li>{{ $message }}</li>
                        @enderror
                        @error('password')
                            <li>{{ $message }}</li>
                        @enderror
                        @error('password_confirmation')
                            <li>{{ $message }}</li>
                        @enderror
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT') 

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat
                        Ini</label>
                    <input type="password" name="current_password" id="current_password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('current_password') border-red-500 @enderror">
                    @error('current_password')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi
                        Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('password_confirmation') border-red-500 @enderror">
                    {{-- Error spesifik untuk password_confirmation --}}
                    @error('password_confirmation')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                    Ganti Password
                </button>
            </form>

            <button id="closeChangePasswordModal"
                class="mt-4 w-full text-gray-500 hover:text-gray-700 text-sm">Tutup</button>
        </div>
    </div>

    @if (session('success_message_qr'))
        @php
            $qr_notification = session('success_message_qr');
            $bg_color =
                $qr_notification['type'] === 'success'
                    ? 'bg-green-100 border-green-400 text-green-700'
                    : 'bg-blue-100 border-blue-400 text-blue-700';
        @endphp
        <div class="mb-8 {{ $bg_color }} px-4 py-3 rounded-lg">
            <p class="font-semibold">{{ $qr_notification['title'] }}</p>
            <p>{{ $qr_notification['message'] }}</p>
        </div>
    @endif

    @if (session('payment_details'))
        @php
            $parkingId = session('payment_details.parking_id');
            $parkingFee = session('payment_details.fee');
        @endphp

        <div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Parkir Selesai!</h3>
                <p class="text-lg text-gray-700 mb-6 text-center">Total biaya parkir Anda:</p>
                <p class="text-5xl font-extrabold text-green-600 mb-8 text-center">Rp
                    {{ number_format($parkingFee, 0, ',', '.') }}</p>

                <p class="text-md text-gray-700 mb-4 text-center">Pilih metode pembayaran:</p>

                <div id="paymentOptions" class="space-y-4">
                    <button data-parking-id="{{ $parkingId }}" id="btnCash"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center space-x-2">
                        <i class="fas fa-money-bill-wave text-xl"></i>
                        <span>Bayar Tunai</span>
                    </button>
                    <button data-parking-id="{{ $parkingId }}" data-fee="{{ $parkingFee }}" id="btnQRScan"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center space-x-2">
                        <i class="fas fa-qrcode text-xl"></i>
                        <span>Scan QR</span>
                    </button>
                </div>

                <div id="qrSection" class="hidden mt-6 text-center">
                    <h4 class="text-xl font-semibold mb-4">Scan untuk Membayar</h4>
                    <img id="qrCodeImage" src="https://via.placeholder.com/200?text=Memuat+QR..." alt="QR Code Pembayaran"
                        class="mx-auto border border-gray-300 rounded-lg p-2 mb-4">
                    <p class="text-sm text-gray-600">Scan QR Code di atas menggunakan aplikasi pembayaran Anda.</p>
                    <p class="text-sm text-gray-600 mt-2" id="qr_status_message">Menunggu scan...</p> {{-- Tambahan untuk status QR --}}
                    <button id="btnBackToOptions" class="mt-4 text-blue-600 hover:underline">Kembali ke Pilihan
                        Pembayaran</button>
                </div>

                <button id="closeModal" class="mt-6 w-full text-gray-500 hover:text-gray-700 text-sm">Tutup
                    Notifikasi</button>
            </div>
        </div>
    @endif

    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">Status Parkir Aktif</h2>
                        @if ($activeParking)
                            <p class="text-blue-100">Kendaraan Anda sedang parkir</p>
                        @else
                            <p class="text-blue-100">Kendaraan Anda sedang tidak parkir</p>
                        @endif
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
                                    @if ($activeParking && $activeParking->vehicle)
                                        {{ $activeParking->vehicle->nopol }}
                                    @else
                                        Tidak ada parkir aktif
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="bg-green-100 p-2 rounded-lg">
                                <i class="fas fa-map-marker-alt text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Lokasi Parkir</p>
                                <p class="font-bold text-lg">
                                    @if ($activeParking && $activeParking->areaParkir)
                                        {{ $activeParking->areaParkir->name }} - {{ $activeParking->keterangan }}
                                    @else
                                        Tidak ada parkir aktif
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="bg-purple-100 p-2 rounded-lg">
                                <i class="fas fa-clock text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Waktu Masuk</p>
                                <p class="font-bold text-lg">
                                    @if ($activeParking)
                                        {{ Carbon\Carbon::parse($activeParking->start)->format('H:i') }} WIB
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <p class="text-sm text-gray-500">
                                    @if ($activeParking)
                                        {{ Carbon\Carbon::parse($activeParking->tanggal)->format('d F Y') }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600">Durasi Parkir</span>
                                <span class="text-2xl font-bold text-blue-600"
                                    id="duration">{{ $activeParking ? $activeParking->calculateDuration($activeParking->start) : '0j 0m' }}</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600">Tarif per Jam</span>
                                <span class="font-semibold">
                                    Rp
                                    {{ number_format($activeParking && $activeParking->vehicle && $activeParking->vehicle->vehicleType ? $activeParking->vehicle->vehicleType->fee : 0, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="border-t pt-2 mt-2">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold">Estimasi Biaya</span>
                                    <span class="text-xl font-bold text-red-600">Rp
                                        {{ number_format($activeParking ? $activeParking->calculateFee($activeParking->start, now()->toTimeString(), $activeParking->vehicle->vehicleType) : 0, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        @if ($activeParking)
                            <form action="{{ route('parking.show_payment', $activeParking->id) }}" method="GET">
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

    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800 flex items-center space-x-2">
                <i class="fas fa-history text-blue-600"></i>
                <span>Riwayat Parkir</span>
            </h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @if ($pastParkings && $pastParkings->count() > 0)
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
                                    {{ $parking->calculateDuration($parking->start, $parking->end) }}</p>
                                <p class="font-bold text-green-600">Rp {{ number_format($parking->biaya, 0, ',', '.') }}
                                </p>
                                <span
                                    class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Selesai</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-600 text-center">Belum ada riwayat parkir yang selesai.</p>
                @endif

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function updateDuration(startTime) {
            const now = new Date();
            const [startHour, startMinute] = startTime.split(':').map(Number);

            const start = new Date(
                now.getFullYear(),
                now.getMonth(),
                now.getDate(),
                startHour,
                startMinute,
                0
            );

            let diff = now.getTime() - start.getTime();

            if (diff < 0) {
                start.setDate(start.getDate() - 1);
                diff = now.getTime() - start.getTime();
            }

            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

            document.getElementById('duration').textContent = `${hours}j ${minutes}m`;
        }

        let pollingIntervalId; // Variabel untuk menyimpan ID interval polling

        function startPolling(parkingId) {
            // Hentikan polling sebelumnya jika ada
            if (pollingIntervalId) {
                clearInterval(pollingIntervalId);
            }

            const qrStatusMessage = document.getElementById('qr_status_message');
            qrStatusMessage.textContent = 'Menunggu pembayaran...';

            pollingIntervalId = setInterval(() => {
                fetch(`{{ url('parking') }}/${parkingId}/check-payment-status`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'paid') {
                            qrStatusMessage.textContent = data.message;
                            clearInterval(pollingIntervalId); // Hentikan polling
                            alert(data.message + " Halaman akan di-refresh.");
                            window.location.reload(); // Refresh halaman setelah pembayaran berhasil
                        } else if (data.status === 'pending') {
                            qrStatusMessage.textContent = data.message;
                        } else { // Failed atau status tidak dikenal
                            qrStatusMessage.textContent = data.message + " Silakan coba lagi.";
                            clearInterval(pollingIntervalId); // Hentikan polling jika gagal
                        }
                    })
                    .catch(error => {
                        console.error('Error polling payment status:', error);
                        qrStatusMessage.textContent = 'Gagal memeriksa status pembayaran.';
                        clearInterval(pollingIntervalId);
                    });
            }, 3000); // Polling setiap 3 detik
        }

        document.addEventListener('DOMContentLoaded', () => {

            const changePasswordModal = document.getElementById('changePasswordModal');
            const openChangePasswordModalBtn = document.getElementById('openChangePasswordModal');
            const closeChangePasswordModalBtn = document.getElementById('closeChangePasswordModal');

            @if ($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
                changePasswordModal.classList.remove('hidden');
            @endif

            if (openChangePasswordModalBtn) {
                openChangePasswordModalBtn.addEventListener('click', () => {
                    changePasswordModal.classList.remove('hidden');
                });
            }

            if (closeChangePasswordModalBtn) {
                closeChangePasswordModalBtn.addEventListener('click', () => {
                    changePasswordModal.classList.add('hidden');
                });
            }
            
            const activeParkingStart = "{{ $activeParking->start ?? '' }}";
            if (activeParkingStart) {
                updateDuration(activeParkingStart);
                setInterval(() => updateDuration(activeParkingStart), 60000);
            }

            const paymentModal = document.getElementById('paymentModal');
            const paymentOptions = document.getElementById('paymentOptions');
            const btnCash = document.getElementById('btnCash');
            const btnQRScan = document.getElementById('btnQRScan');
            const qrSection = document.getElementById('qrSection');
            const qrCodeImage = document.getElementById('qrCodeImage');
            const btnBackToOptions = document.getElementById('btnBackToOptions');
            const closeModal = document.getElementById('closeModal');

            @if (session('payment_details'))
                paymentModal.classList.remove('hidden');
                // Jika modal muncul dari refresh, pastikan polling tidak langsung jalan kecuali setelah klik QR
            @endif

            if (btnCash) {
                btnCash.addEventListener('click', () => {
                    const parkingId = btnCash.dataset.parkingId;
                    clearInterval(pollingIntervalId); // Hentikan polling jika beralih ke tunai

                    fetch(`{{ url('parking') }}/${parkingId}/complete-cash`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(
                                    `Anda memilih pembayaran Tunai. Parkir selesai. Biaya: Rp ${data.fee_formatted}. Silakan lakukan pembayaran di kasir.`
                                );
                                paymentModal.classList.add('hidden');
                                window.location.reload();
                            } else {
                                alert('Gagal menyelesaikan pembayaran tunai: ' + (data.message ||
                                    'Terjadi kesalahan.'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat memproses pembayaran tunai.');
                        });
                });
            }

            if (btnQRScan) {
                btnQRScan.addEventListener('click', () => {
                    const parkingId = btnQRScan.dataset.parkingId;
                    const fee = btnQRScan.dataset.fee;

                    paymentOptions.classList.add('hidden');
                    qrSection.classList.remove('hidden');
                    qrCodeImage.src = 'https://via.placeholder.com/200?text=Memuat+QR...';

                    fetch(`{{ url('parking') }}/${parkingId}/generate-qr`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                fee: fee
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.qr_code_url) {
                                qrCodeImage.src = data.qr_code_url;
                                // Mulai polling setelah QR ditampilkan
                                startPolling(parkingId);
                            } else {
                                alert('Gagal menghasilkan QR Code: ' + (data.message ||
                                    'Terjadi kesalahan.'));
                                qrCodeImage.src = 'https://via.placeholder.com/200?text=Error+QR';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat menghasilkan QR Code.');
                            qrCodeImage.src = 'https://via.placeholder.com/200?text=Error+QR';
                        });
                });
            }

            if (btnBackToOptions) {
                btnBackToOptions.addEventListener('click', () => {
                    clearInterval(pollingIntervalId); // Hentikan polling saat kembali ke opsi
                    paymentOptions.classList.remove('hidden');
                    qrSection.classList.add('hidden');
                    qrCodeImage.src = 'https://via.placeholder.com/200?text=Memuat+QR...';
                });
            }

            if (closeModal) {
                closeModal.addEventListener('click', () => {
                    clearInterval(pollingIntervalId); // Hentikan polling saat menutup modal
                    paymentModal.classList.add('hidden');
                });
            }
        });
    </script>
@endsection
