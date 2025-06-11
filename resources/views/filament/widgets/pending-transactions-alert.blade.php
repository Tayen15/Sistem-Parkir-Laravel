<x-filament-widgets::widget>
    <x-filament::section>
        <x-filament::card class="fi-card">
            <h3 class="fi-card-header-heading text-lg font-semibold tracking-tight text-gray-950 dark:text-white">
                Perhatian: Transaksi Parkir Aktif!
            </h3>
            <p class="fi-card-description text-gray-500 dark:text-gray-400">
                Ada <span class="text-xl font-extrabold text-danger-600">{{ $this->getTransactionsCount() }}</span>
                kendaraan yang saat ini masih dalam status parkir aktif.
            </p>
            @if ($this->getTransactionsCount() > 0)
                <div class="mt-4">
                    <a href="{{ \App\Filament\Resources\TransactionResource::getUrl('index', ['tableFilters' => ['end' => ['value' => false]]]) }}"
                        class="filament-button inline-flex items-center justify-center font-semibold rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 fi-btn-link fi-btn-color-primary fi-btn-size-md text-primary-600 hover:text-primary-500 dark:text-primary-500 dark:hover:text-primary-400 focus:ring-primary-500 dark:focus:ring-primary-400">
                        Lihat Transaksi Aktif
                    </a>
                </div>
            @endif
        </x-filament::card>
    </x-filament::section>
</x-filament-widgets::widget>
