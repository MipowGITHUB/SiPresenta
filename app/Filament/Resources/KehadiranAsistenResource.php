<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KehadiranAsistenResource\Pages;
use App\Filament\Resources\KehadiranAsistenResource\RelationManagers;
use App\Models\KehadiranAsisten;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KehadiranAsistenResource extends Resource
{
    protected static ?string $model = KehadiranAsisten::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('asisten.nama')->label('Asisten'),
                Tables\Columns\TextColumn::make('pertemuan.judul')->label('Pertemuan'),
                Tables\Columns\TextColumn::make('alatRfid.uid')->label('UID Alat'),
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
            'index' => Pages\ListKehadiranAsistens::route('/'),
            'create' => Pages\CreateKehadiranAsisten::route('/create'),
            'edit' => Pages\EditKehadiranAsisten::route('/{record}/edit'),
        ];
    }
}
