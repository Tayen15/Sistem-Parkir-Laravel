<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentTransactionsTable extends BaseWidget
{
    protected static ?int $sort = -1;

    protected static ?string $heading = '5 Transaksi Terbaru';

    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return Transaction::query()
            ->whereNotNull('end')
            ->latest()
            ->limit(5)
            ->with(['vehicle.vehicleType', 'areaParkir']);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('vehicle.nopol')->label('Nopol'),
            Tables\Columns\TextColumn::make('areaParkir.name')->label('Area'),
            Tables\Columns\TextColumn::make('start')->time()->label('Masuk'),
            Tables\Columns\TextColumn::make('end')
                ->label('Keluar')
                ->time()
                ->default('Aktif'), // Fallback jika somehow end null (tapi harusnya tidak di query ini)
            Tables\Columns\TextColumn::make('biaya')
                ->money('IDR', 0, '.', ',')
                ->label('Biaya'),
            Tables\Columns\TextColumn::make('status_pembayaran')
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    'completed' => 'success',
                    'pending' => 'gray',
                    'awaiting_payment', 'pending_qr' => 'warning',
                    'failed' => 'danger',
                    default => 'secondary',
                })
                ->label('Status'),
        ];
    }

    // Opsional: Untuk menambah tindakan pada setiap baris widget
    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make(),
        ];
    }
}
