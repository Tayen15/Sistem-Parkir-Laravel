<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use App\Models\Vehicle;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = -2;

    protected function getStats(): array
    {
        $totalVehicles = Vehicle::count();
        $activeParkings = Transaction::whereNull('end')->count();
        $dailyRevenue = Transaction::whereNotNull('end')
            ->whereDate('tanggal', now()->toDateString())
            ->sum('biaya');
        $totalTransactions = Transaction::whereNotNull('end')->count();

        return [
            Stat::make('Total Kendaraan Terdaftar', $totalVehicles)
                ->description('Jumlah seluruh kendaraan')
                ->descriptionIcon('heroicon-m-truck')
                ->color('info'), // Warna badge

            Stat::make('Parkir Aktif Saat Ini', $activeParkings)
                ->description('Kendaraan yang sedang parkir')
                ->descriptionIcon('heroicon-m-arrow-up-on-square-stack')
                ->color('warning'),

            Stat::make('Pendapatan Hari Ini', 'Rp ' . number_format($dailyRevenue, 0, ',', '.'))
                ->description('Total biaya parkir hari ini')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('Total Transaksi Selesai', $totalTransactions)
                ->description('Total parkir yang sudah selesai')
                ->descriptionIcon('heroicon-m-receipt-percent')
                ->color('primary'),
        ];
    }
}
