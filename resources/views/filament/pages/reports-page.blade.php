<x-filament-panels::page>
    <div class="fi-dashboard-page">
        <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
            Laporan Detail & Analisis
        </h1>

        <div class="mt-4">

            <div class="my-8">
                <x-filament::card class="fi-card">
                    <h3
                        class="fi-card-header-heading text-lg font-semibold tracking-tight text-gray-950 dark:text-white">
                        Laporan Total Pendapatan
                    </h3>
                    <p class="fi-card-description text-gray-500 dark:text-gray-400">
                        Total Pendapatan Akumulatif: <span class="font-bold text-xl">Rp
                            {{ number_format(\App\Models\Transaction::whereNotNull('end')->sum('biaya'), 0, ',', '.') }}</span>
                    </p>
                </x-filament::card>
            </div>

            <div class="mt-8">
                <h2 class="text-xl font-bold tracking-tight text-gray-950 dark:text-white mb-4">
                    Semua Transaksi Selesai
                </h2>
                
            </div>

        </div>
    </div>
</x-filament-panels::page>
