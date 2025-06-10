<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PraktikanResource\Pages;
use App\Models\Praktikan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PraktikanResource extends Resource
{
    protected static ?string $model = Praktikan::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')->required(),
                Forms\Components\TextInput::make('nim')->required(),
                Forms\Components\TextInput::make('fingerprint_id')->required(),
                Forms\Components\Select::make('praktikums')
                    ->label('Praktikum')
                    ->relationship('praktikums', 'matkul')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->matkul} - {$record->kelas}")
                    ->multiple()
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama'),
                Tables\Columns\TextColumn::make('nim'),
                Tables\Columns\TextColumn::make('fingerprint_id'),
                Tables\Columns\TextColumn::make('praktikums')
                    ->label('Praktikum')
                    ->html()
                    ->alignCenter()
                    ->formatStateUsing(fn ($state, $record) =>
                        $record->praktikums->isNotEmpty()
                            ? '<div class="flex justify-center items-center h-full">
                                    <a href="' . route('filament.admin.resources.praktikans.view-kelas', ['record' => $record->id]) . '" 
                                       class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-primary-600 rounded hover:bg-primary-700">
                                        Detail
                                    </a>
                               </div>'
                            : '-'
                    ),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPraktikans::route('/'),
            'create' => Pages\CreatePraktikan::route('/create'),
            'edit' => Pages\EditPraktikan::route('/{record}/edit'),
            'view-kelas' => Pages\ViewKelasPraktikan::route('/{record}/view-kelas'),
        ];
    }
}
