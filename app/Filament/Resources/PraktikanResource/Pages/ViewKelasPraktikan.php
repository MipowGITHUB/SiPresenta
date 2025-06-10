<?php

namespace App\Filament\Resources\PraktikanResource\Pages;

use App\Filament\Resources\PraktikanResource;
use App\Models\Praktikan;
use Filament\Resources\Pages\Page;

class ViewKelasPraktikan extends Page
{
    protected static string $resource = PraktikanResource::class;

    protected static string $view = 'filament.resources.praktikan-resource.pages.view-kelas-praktikan';

    public Praktikan $praktikan;

    public function mount($record): void
    {
        $this->praktikan = Praktikan::findOrFail($record);
    }

    public function getViewData(): array
    {
        return [
            'praktikan' => $this->praktikan,
            'kelasList' => $this->praktikan->praktikums ?? [],
        ];
    }
}
