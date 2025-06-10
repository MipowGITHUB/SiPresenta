<?php

namespace App\Filament\Resources\PraktikumResource\Pages;

use App\Filament\Resources\PraktikumResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPraktikum extends EditRecord
{
    protected static string $resource = PraktikumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
