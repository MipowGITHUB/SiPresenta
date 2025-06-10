<?php

namespace App\Filament\Resources\KehadiranPraktikanResource\Pages;

use App\Filament\Resources\KehadiranPraktikanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKehadiranPraktikan extends EditRecord
{
    protected static string $resource = KehadiranPraktikanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
