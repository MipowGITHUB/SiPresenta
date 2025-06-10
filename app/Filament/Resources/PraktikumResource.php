<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PraktikumResource\Pages;
use App\Filament\Resources\PraktikumResource\RelationManagers;
use App\Models\Praktikum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PraktikumResource extends Resource
{
    protected static ?string $model = Praktikum::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kelas')->required(),
            Forms\Components\TextInput::make('matkul')->required(),
            Forms\Components\TextInput::make('lab')->required(),
            Forms\Components\Select::make('hari')
                    ->label('Hari')
                    ->options([
                        'Senin' => 'Senin',
                        'Selasa' => 'Selasa',
                        'Rabu' => 'Rabu',
                        'Kamis' => 'Kamis',
                        'Jumat' => 'Jumat',
                        'Sabtu' => 'Sabtu',
                    ])
                    ->required(),
            Forms\Components\TimePicker::make('waktu_mulai')->required(),
            Forms\Components\TimePicker::make('waktu_selesai')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kelas'),
            Tables\Columns\TextColumn::make('matkul'),
            Tables\Columns\TextColumn::make('lab'),
            Tables\Columns\TextColumn::make('hari')->label('Hari'),
            Tables\Columns\TextColumn::make('waktu_mulai'),
            Tables\Columns\TextColumn::make('waktu_selesai'),
            ])
            ->filters([
                //
            ])
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPraktikums::route('/'),
            'create' => Pages\CreatePraktikum::route('/create'),
            'edit' => Pages\EditPraktikum::route('/{record}/edit'),
        ];
    }
}
