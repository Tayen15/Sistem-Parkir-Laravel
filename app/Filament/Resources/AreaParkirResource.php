<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AreaParkirResource\Pages;
use App\Filament\Resources\AreaParkirResource\RelationManagers;
use App\Models\AreaParkir;
use App\Models\Campus;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AreaParkirResource extends Resource
{
    protected static ?string $model = AreaParkir::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup = 'Manajemen Parkir';
    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Area Parkir';
    protected static ?string $pluralModelLabel = 'Daftar Area Parkir';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true, table: AreaParkir::class)
                    ->placeholder('Ex: Gedung A, Gedung B'),
                TextInput::make('kapasitas')
                    ->label('Kapasitas Total')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->placeholder('Ex: 100'),
                Select::make('campus_id')
                    ->label('Kampus')
                    ->relationship('campus', 'name')
                    ->searchable()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Nama Kampus Baru')
                            ->required()
                            ->maxLength(255)
                            ->unique(table: Campus::class, ignoreRecord: true),
                        TextInput::make('address')
                            ->label('Alamat Kampus')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(fn(array $data): int => Campus::create($data)->id),
                TextInput::make('keterangan')
                    ->maxLength(255)
                    ->nullable()
                    ->placeholder('Ex: Depan Lobby, Belakang Kantin'),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kapasitas')
                    ->label('Kapasitas')
                    ->sortable(),
                TextColumn::make('campus.name')
                    ->label('Kampus')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('keterangan'),
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
                SelectFilter::make('campus_id')
                    ->relationship('campus', 'name')
                    ->label('Filter Kampus'),
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
            'index' => Pages\ManageAreaParkirs::route('/'),
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
