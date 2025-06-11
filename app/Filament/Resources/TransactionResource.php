<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use App\Models\Vehicle;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?string $navigationGroup = 'Manajemen Parkir';
    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Transaksi Parkir';
    protected static ?string $pluralModelLabel = 'Daftar Transaksi Parkir';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kendaraan_id')
                    ->label('Kendaraan (Nopol)')
                    ->relationship('vehicle', 'nopol')
                    ->searchable()
                    ->required(),
                Select::make('area_parkir_id')
                    ->label('Area Parkir')
                    ->relationship('areaParkir', 'name')
                    ->searchable()
                    ->required(),
                DatePicker::make('tanggal')
                    ->required()
                    ->default(now()),
                TimePicker::make('start')
                    ->label('Waktu Masuk')
                    ->required()
                    ->default(now()->format('H:i')),
                TimePicker::make('end')
                    ->label('Waktu Keluar')
                    ->nullable()
                    ->live()
                    ->afterStateUpdated(function (?string $state, Forms\Get $get, Forms\set $set) {
                        $startTime = $get('start');
                        $vehicleId = $get('kendaraan_id');

                        if ($state && $startTime && $vehicleId) {
                            $transaction = new Transaction();
                            $vehicle = Vehicle::find($vehicleId);

                            if ($vehicle) {
                                $fee = $transaction->calculateFee($startTime, $state, $vehicle->vehicleType);
                                $set('biaya', $fee);
                            }
                        } else {
                            $set('biaya', 0);
                        }
                    }),
                TextInput::make('keterangan')
                    ->label('Slot/Keterangan')
                    ->maxLength(255)
                    ->nullable(),
                TextInput::make('biaya')
                    ->label('Biaya')
                    ->numeric()
                    ->default(0)
                    ->mask(RawJs::make('$money($Input)'))
                    ->prefix('Rp')
                    ->stripCharacters(',')
                    ->dehydrateStateUsing(fn($state) => (float) str_replace(['.', 'Rp ', ' '], '', $state))
                    ->readOnly(),
                Select::make('status_pembayaran')
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Pending',
                        'awaiting_payment' => 'Menunggu Pembayaran',
                        'pending_qr' => 'Menunggu Scan QR',
                        'completed' => 'Selesai Dibayar',
                        'failed' => 'Gagal',
                    ])
                    ->default('pending')
                    ->required(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vehicle.nopol')
                    ->label('Kendaraan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('areaParkir.name')
                    ->label('Area Parkir')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('start')
                    ->label('Masuk')
                    ->time()
                    ->sortable(),
                TextColumn::make('end')
                    ->label('Keluar')
                    ->time()
                    ->placeholder('Belum Keluar')
                    ->sortable(),
                TextColumn::make('keterangan')
                    ->label('Slot'),
                TextColumn::make('biaya')
                    ->label('Biaya')
                    ->numeric()
                    ->money('IDR', 0, '.', ',')
                    ->sortable(),
                TextColumn::make('status_pembayaran')
                    ->label('Status Bayar')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'gray',
                        'awaiting_payment', 'pending_qr' => 'warning',
                        'failed' => 'danger',
                        default => 'secondary',
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('vehicle_id')
                    ->relationship('vehicle', 'nopol')
                    ->label('Filter Kendaraan'),
                SelectFilter::make('area_parkir_id')
                    ->relationship('areaParkir', 'name')
                    ->label('Filter Area Parkir'),
                SelectFilter::make('status_pembayaran')
                    ->options([
                        'pending' => 'Pending',
                        'awaiting_payment' => 'Menunggu Pembayaran',
                        'pending_qr' => 'Menunggu Scan QR',
                        'completed' => 'Selesai Dibayar',
                        'failed' => 'Gagal',
                    ])
                    ->label('Filter Status Pembayaran'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTransactions::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
