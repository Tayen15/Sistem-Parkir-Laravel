{{-- resources/views/payment/confirmation.blade.php --}}
@extends('layouts.app') {{-- Atau layout minimal jika Anda punya --}}

@section('title', 'Konfirmasi Pembayaran')

@section('content')
    <div class="min-h-full flex items-center justify-center bg-gray-100">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full text-center">
            @if ($status === 'success')
                <div class="text-green-500 mb-4">
                    <i class="fas fa-check-circle text-6xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Pembayaran Berhasil!</h2>
                <p class="text-lg text-gray-700 mb-6">Transaksi parkir Anda dengan ID:
                    <strong>#{{ $transaction->id }}</strong> telah berhasil dibayar.</p>
                <p class="text-2xl font-extrabold text-green-600 mb-8">Total Biaya: Rp
                    {{ number_format($transaction->biaya, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-600">Terima kasih telah menggunakan layanan kami.</p>
                <div class="mt-6">
                    <a href="{{ route('login') }}"
                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">Login
                        ke Dashboard</a>
                </div>
            @elseif ($status === 'info')
                <div class="text-blue-500 mb-4">
                    <i class="fas fa-info-circle text-6xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Informasi Transaksi</h2>
                <p class="text-lg text-gray-700 mb-6">Parkir dengan ID: <strong>#{{ $transaction->id }}</strong> ini sudah
                    selesai dibayar sebelumnya.</p>
                <p class="text-sm text-gray-600">Anda dapat login untuk melihat riwayat.</p>
                <div class="mt-6">
                    <a href="{{ route('login') }}"
                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">Login
                        ke Dashboard</a>
                </div>
            @else
                <div class="text-red-500 mb-4">
                    <i class="fas fa-times-circle text-6xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Pembayaran Gagal/Error</h2>
                <p class="text-lg text-gray-700 mb-6">Terjadi masalah saat memproses pembayaran Anda. Silakan coba lagi atau
                    hubungi admin.</p>
                <p class="text-sm text-gray-600">ID Transaksi: {{ $transaction->id ?? 'N/A' }}</p>
                <div class="mt-6">
                    <a href="{{ route('login') }}"
                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">Login
                        ke Dashboard</a>
                </div>
            @endif
        </div>
    </div>
@endsection
