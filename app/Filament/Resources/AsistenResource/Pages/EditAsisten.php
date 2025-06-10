<?php

namespace App\Filament\Resources\AsistenResource\Pages;

use App\Filament\Resources\AsistenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAsisten extends EditRecord
{
    protected static string $resource = AsistenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
