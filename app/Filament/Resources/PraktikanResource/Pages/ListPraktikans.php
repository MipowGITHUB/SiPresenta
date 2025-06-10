<?php

namespace App\Filament\Resources\PraktikanResource\Pages;

use App\Filament\Resources\PraktikanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPraktikans extends ListRecords
{
    protected static string $resource = PraktikanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
