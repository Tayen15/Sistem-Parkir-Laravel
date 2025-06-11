<x-filament-widgets::widget>
        @php
            $almostFullAreas = $this->getAlmostFullAreas();
        @endphp

        @if ($this->hasAlmostFullAreas())
            <x-filament::card class="fi-card border-l-4 border-orange-500 bg-orange-50 dark:bg-orange-950">
                <h3
                    class="fi-card-header-heading text-lg font-semibold tracking-tight text-orange-800 dark:text-orange-200 flex items-center gap-2">
                    <x-heroicon-o-exclamation-triangle class="h-6 w-6 text-orange-500" />
                    Peringatan: Parkir Hampir Penuh!
                </h3>
                <p class="fi-card-description text-orange-700 dark:text-orange-300">
                    Beberapa area parkir mendekati kapasitas penuh. Mohon periksa segera.
                </p>

                <ul class="mt-4 space-y-2 text-sm text-orange-700 dark:text-orange-300">
                    @foreach ($almostFullAreas as $area)
                        <li>
                            <strong>{{ $area->name }}</strong>: Tersisa {{ $area->available_slots_count }} slot dari
                            {{ $area->kapasitas }} (Okupansi {{ round($area->occupancy_percentage * 100) }}%)
                            @if ($area->available_slots_count <= 0)
                                <span class="text-danger-600 font-bold ml-2"> (PENUH!)</span>
                            @endif
                        </li>
                    @endforeach
                </ul>

                <div class="mt-4">
                    <a href="{{ \App\Filament\Resources\AreaParkirResource::getUrl('index') }}"
                        class="filament-button inline-flex items-center justify-center font-semibold rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 fi-btn-link fi-btn-color-warning fi-btn-size-md text-orange-600 hover:text-orange-500 dark:text-orange-500 dark:hover:text-orange-400 focus:ring-orange-500 dark:focus:ring-orange-400">
                        Kelola Area Parkir
                    </a>
                </div>
            </x-filament::card>
        @endif
</x-filament-widgets::widget>
