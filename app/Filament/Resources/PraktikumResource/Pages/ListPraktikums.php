<?php

namespace App\Filament\Resources\PraktikumResource\Pages;

use App\Filament\Resources\PraktikumResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPraktikums extends ListRecords
{
    protected static string $resource = PraktikumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
