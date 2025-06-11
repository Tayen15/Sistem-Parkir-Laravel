<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentTransactionsTable extends BaseWidget
{
    protected static ?int $sort = -1;

    protected static ?string $heading = '5 Transaksi Terbaru';

    protected static ?string $pollingInterval = '20s';

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
                ->default('Aktif'), 
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

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make()
                ->form([
                    Select::make('kendaraan_id')
                        ->label('Kendaraan (Nopol)')
                        ->relationship('vehicle', 'nopol')
                        ->disabled(), 
                    Select::make('area_parkir_id')
                        ->label('Area Parkir')
                        ->relationship('areaParkir', 'name')
                        ->disabled(),
                    DatePicker::make('tanggal')
                        ->disabled(),
                    TimePicker::make('start')
                        ->label('Waktu Masuk')
                        ->disabled(),
                    TimePicker::make('end')
                        ->label('Waktu Keluar')
                        ->disabled(),
                    TextInput::make('keterangan')
                        ->label('Slot/Keterangan')
                        ->disabled(),
                    TextInput::make('biaya')
                        ->label('Biaya')
                        ->numeric()
                        ->mask(RawJs::make('$money($input)'))
                        ->prefix('Rp ')
                        ->disabled(), // Buat disabled
                    TextInput::make('status_pembayaran')
                        ->label('Status Pembayaran')
                        ->disabled(),
                ]),
        ];
    }
}
