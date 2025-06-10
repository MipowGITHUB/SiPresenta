<?php

namespace App\Filament\Resources\AsistenResource\Pages;

use App\Filament\Resources\AsistenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAsistens extends ListRecords
{
    protected static string $resource = AsistenResource::class;

    public function getTitle():string{
        return 'Assistant';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
