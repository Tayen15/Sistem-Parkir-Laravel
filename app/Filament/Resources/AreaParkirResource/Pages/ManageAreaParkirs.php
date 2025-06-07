<?php

namespace App\Filament\Resources\AreaParkirResource\Pages;

use App\Filament\Resources\AreaParkirResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAreaParkirs extends ManageRecords
{
    protected static string $resource = AreaParkirResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
