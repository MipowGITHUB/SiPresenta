<?php

namespace App\Filament\Resources\KehadiranAsistenResource\Pages;

use App\Filament\Resources\KehadiranAsistenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKehadiranAsistens extends ListRecords
{
    protected static string $resource = KehadiranAsistenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
