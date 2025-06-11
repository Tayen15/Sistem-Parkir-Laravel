<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = -2;

    protected static ?string $pollingInterval = '20s';

    protected function getStats(): array
    {
        $totalVehicles = Vehicle::count();
        $activeParkings = Transaction::whereNull('end')->count();
        $dailyRevenue = Transaction::whereNotNull('end')
            ->whereDate('tanggal', now()->toDateString())
            ->sum('biaya');
        $totalTransactions = Transaction::whereNotNull('end')->count();
        $totalUsers = User::count();

        $completedTransactions = Transaction::whereNotNull('end')->get(); 

        $totalDurationInMinutes = 0;
        if ($completedTransactions->isNotEmpty()) {
            foreach ($completedTransactions as $transaction) {
                $startTime = Carbon::parse($transaction->start);
                $endTime = Carbon::parse($transaction->end);
                $totalDurationInMinutes += $startTime->diffInMinutes($endTime);
            }
            $averageDurationInMinutes = $totalDurationInMinutes / $completedTransactions->count();
        } else {
            $averageDurationInMinutes = 0;
        }

        $avgHours = floor($averageDurationInMinutes / 60);
        $avgMinutes = round($averageDurationInMinutes % 60); 
        $averageDurationFormatted = "{$avgHours}j {$avgMinutes}m";

        return [
            Stat::make('Total Kendaraan Terdaftar', $totalVehicles)
                ->description('Jumlah seluruh kendaraan')
                ->descriptionIcon('heroicon-m-truck')
                ->color('info'),

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

            Stat::make('Jumlah Pengguna Terdaftar', $totalUsers)
                ->description('Total akun pengguna di sistem')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Rata-rata Durasi Parkir', $averageDurationFormatted)
                ->description('Durasi rata-rata per parkir')
                ->descriptionIcon('heroicon-m-clock')
                ->color('secondary'),
        ];
    }
}
