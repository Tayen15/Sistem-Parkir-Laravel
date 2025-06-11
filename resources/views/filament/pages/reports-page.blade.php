{{-- resources/views/filament/pages/reports-page.blade.php --}}

<x-filament-panels::page>
    <div class="fi-dashboard-page">
        <div class="py-4"> 
            <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white mb-6">
                Laporan Detail & Analisis
            </h1>
        </div>

        @php
            $reportData = $this->getDailyReportData(); 
        @endphp


        <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <x-filament::card class="fi-card">
                <h3 class="fi-card-header-heading text-lg font-semibold tracking-tight text-gray-950 dark:text-white">
                    Total Kendaraan
                </h3>
                <p class="fi-card-description text-gray-500 dark:text-gray-400">
                    <span class="text-3xl font-extrabold text-blue-600">{{ $reportData['total_vehicles'] }}</span>
                </p>
            </x-filament::card>

            <x-filament::card class="fi-card">
                <h3 class="fi-card-header-heading text-lg font-semibold tracking-tight text-gray-950 dark:text-white">
                    Total Pendapatan Bulan Ini
                </h3>
                <p class="fi-card-description text-gray-500 dark:text-gray-400">
                    <span class="text-3xl font-extrabold text-purple-600">Rp
                        {{ number_format($reportData['monthly_revenue'], 0, ',', '.') }}</span>
                </p>
            </x-filament::card>

            <x-filament::card class="fi-card">
                <h3 class="fi-card-header-heading text-lg font-semibold tracking-tight text-gray-950 dark:text-white">
                    Parkir Aktif
                </h3>
                <p class="fi-card-description text-gray-500 dark:text-gray-400">
                    <span class="text-3xl font-extrabold text-orange-600">{{ $reportData['active_parkings'] }}</span>
                </p>
            </x-filament::card>

            <x-filament::card>
                <h3 class="fi-card-header-heading text-lg font-semibold tracking-tight text-gray-950 dark:text-white">
                    Total Pendapatan Hari ini
                </h3>
                <p class="fi-card-description text-gray-500 dark:text-gray-400">
                    <span class="text-3xl font-extrabold text-purple-600">Rp
                        {{ number_format($reportData['daily_revenue'], 0, ',', '.') }}</span>
                </p>
            </x-filament::card>
        </div>

        <div class="my-2">
            <h2 class="text-xl font-bold tracking-tight text-gray-950 dark:text-white mb-4">
                Tabel Transaksi Lengkap
            </h2>
            {{ $this->table }}
        </div>

    </div>
</x-filament-panels::page>