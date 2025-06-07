<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Filament\Resources\VehicleResource\RelationManagers;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Manajemen Parkir';
    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Kendaraan';
    protected static ?string $pluralModelLabel = 'Daftar Kendaraan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('pemilik')
                    ->label('Pemilik Kendaraan')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('jenis_kendaraan')
                    ->label('Jenis Kendaraan')
                    ->options(VehicleType::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Nama Jenis Kendaraan Baru')
                            ->required()
                            ->maxLength(255)
                            ->unique(table: VehicleType::class, ignoreRecord: true),
                        TextInput::make('fee')
                            ->label('Tarif per Jam (Rp)')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->mask(RawJs::make('$money($input)'))
                            ->prefix('Rp')
                            ->stripCharacters(','),
                    ])
                    ->createOptionUsing(function (array $data): int {
                        $vehicleType = VehicleType::create($data);
                        return $vehicleType->id;
                    }),
                TextInput::make('nopol')
                    ->label('Nomor Polisi')
                    ->required()
                    ->unique(ignoreRecord: true, table: Vehicle::class)
                    ->maxLength(20)
                    ->placeholder('Ex: B 1234 ABC'),
                TextInput::make('merk')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ex: Honda, Toyota'),
                TextInput::make('thn_beli')
                    ->label('Tahun Beli')
                    ->numeric()
                    ->minLength(4)
                    ->maxLength(4)
                    ->minValue(1900)
                    ->maxValue((int)date('Y'))
                    ->required()
                    ->placeholder('Ex: 2020'),
                RichEditor::make('deskripsi')
                    ->maxLength(1000)
                    ->nullable()
                    ->columnSpanFull(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nopol')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Pemilik')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('vehicleType.name')
                    ->label('Jenis')
                    ->sortable(),
                TextColumn::make('merk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('thn_beli')
                    ->label('Tahun Beli')
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
                SelectFilter::make('pemilik')
                    ->relationship('user', 'name')
                    ->label('Filter Pemilik'),
                SelectFilter::make('jenis_kendaraan')
                    ->relationship('vehicleType', 'name')
                    ->label('Filter Jenis Kendaraa'),
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
            'index' => Pages\ManageVehicles::route('/'),
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
