<?php

namespace App\Filament\Resources\PraktikanResource\Pages;

use App\Filament\Resources\PraktikanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPraktikan extends EditRecord
{
    protected static string $resource = PraktikanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
