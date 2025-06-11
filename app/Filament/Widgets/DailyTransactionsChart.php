<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class DailyTransactionsChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Transaksi Per Hari (7 Hari Terakhir)';

    protected static ?int $sort = 2;

    protected static ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        $data = $this->getTransactionsPerDay();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi',
                    'data' => array_values($data),
                    'backgroundColor' => '#2563EB',
                    'borderColor' => '#1D4ED8',
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0, 
                    ],
                ],
            ],
            'plugins' => [
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return context.dataset.label + ": " + context.parsed.y; }',
                    ],
                ],
            ],
        ];
    }

    private function getTransactionsPerDay(): array
    {
        $transactions = Transaction::query()
            ->whereNotNull('end') 
            ->where('tanggal', '>=', Carbon::now()->subDays(6)->toDateString()) 
            ->orderBy('tanggal')
            ->get()
            ->groupBy(fn($item) => Carbon::parse($item->tanggal)->format('D, M d')); 

        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $formattedDate = $date->format('D, M d');
            $labels[$formattedDate] = 0; 
        }

        foreach ($transactions as $dateKey => $group) {
            $labels[$dateKey] = $group->count();
        }

        return $labels; 
    }
}
