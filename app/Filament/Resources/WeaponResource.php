<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WeaponResource\Pages;
use App\Models\Weapon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WeaponResource extends Resource
{
    protected static ?string $model = Weapon::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('skill')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('damage')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('base_range')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('uses_per_round')
                    ->required()
                    ->maxLength(255)
                    ->default(1),
                Forms\Components\TextInput::make('bullets_in_mag')
                    ->maxLength(255),
                Forms\Components\TextInput::make('cost')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('malfunction')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('skill')
                    ->searchable(),
                Tables\Columns\TextColumn::make('damage')
                    ->searchable(),
                Tables\Columns\TextColumn::make('base_range')
                    ->searchable(),
                Tables\Columns\TextColumn::make('uses_per_round')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bullets_in_mag')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cost')
                    ->searchable(),
                Tables\Columns\TextColumn::make('malfunction')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListWeapons::route('/'),
            'create' => Pages\CreateWeapon::route('/create'),
            'edit' => Pages\EditWeapon::route('/{record}/edit'),
        ];
    }
}
