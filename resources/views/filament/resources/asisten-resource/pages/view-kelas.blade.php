<x-filament::page>
    <x-filament::section>
        <x-slot name="header">
            <h2 class="text-xl font-bold">
                Data Praktikum - {{ class_basename($record) }}: {{ $record->nama ?? $record->name ?? 'Tanpa Nama' }}
            </h2>
        </x-slot>

        {{ $this->table }}
    </x-filament::section>
</x-filament::page>
