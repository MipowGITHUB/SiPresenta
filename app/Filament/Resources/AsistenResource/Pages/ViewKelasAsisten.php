<?php

namespace App\Filament\Resources\AsistenResource\Pages;

use App\Filament\Resources\AsistenResource;
use App\Models\Asisten;
use Filament\Resources\Pages\Page;

class ViewKelasAsisten extends Page
{
    protected static string $resource = AsistenResource::class;

    protected static string $view = 'filament.resources.asisten-resource.pages.view-kelas-asisten';

    public Asisten $asisten;

    public function mount($record): void
    {
        $this->asisten = Asisten::findOrFail($record);
    }

    public function getViewData(): array
    {
        return [
            'asisten' => $this->asisten,
            'kelasList' => $this->asisten->praktikums ?? [],
        ];
    }
}
