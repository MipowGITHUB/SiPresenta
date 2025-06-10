<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KehadiranPraktikanResource\Pages;
use App\Filament\Resources\KehadiranPraktikanResource\RelationManagers;
use App\Models\KehadiranPraktikan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KehadiranPraktikanResource extends Resource
{
    protected static ?string $model = KehadiranPraktikan::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('praktikan.nama')->label('Praktikan'),
            Tables\Columns\TextColumn::make('pertemuan.judul')->label('Pertemuan'),
            Tables\Columns\TextColumn::make('alatPresensi.nama')->label('Alat'),
            Tables\Columns\TextColumn::make('waktu_presensi')->label('Waktu')->dateTime(),
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
            'index' => Pages\ListKehadiranPraktikans::route('/'),
            'create' => Pages\CreateKehadiranPraktikan::route('/create'),
            'edit' => Pages\EditKehadiranPraktikan::route('/{record}/edit'),
        ];
    }
}
