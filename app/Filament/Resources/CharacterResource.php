<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CharacterResource\Pages;
use App\Models\Character;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CharacterResource extends Resource
{
    protected static ?string $model = Character::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('user_id')
                    ->numeric(),
                Forms\Components\TextInput::make('occupation')
                    ->maxLength(255),
                Forms\Components\TextInput::make('residence')
                    ->maxLength(255),
                Forms\Components\TextInput::make('birthplace')
                    ->maxLength(255),
                Forms\Components\TextInput::make('age')
                    ->numeric(),
                Forms\Components\TextInput::make('gender')
                    ->maxLength(255),
                Forms\Components\TextInput::make('strength')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('dexterity')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('intelligence')
                    ->tel()
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('constitution')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('appearance')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('power')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('size')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('education')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('move_rate')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('temporary_insanity')
                    ->required(),
                Forms\Components\Toggle::make('indefinite_insanity')
                    ->required(),
                Forms\Components\Toggle::make('major_wound')
                    ->required(),
                Forms\Components\Toggle::make('unconscious')
                    ->required(),
                Forms\Components\Toggle::make('dying')
                    ->required(),
                Forms\Components\TextInput::make('hit_points')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('sanity')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('luck')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('magic_points')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('dodge')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('build')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('damage_bonus')
                    ->required()
                    ->maxLength(255)
                    ->default(0),
                Forms\Components\TextInput::make('avatar')
                    ->maxLength(255),
                Forms\Components\TextInput::make('group_id')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('occupation')
                    ->searchable(),
                Tables\Columns\TextColumn::make('residence')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birthplace')
                    ->searchable(),
                Tables\Columns\TextColumn::make('age')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender')
                    ->searchable(),
                Tables\Columns\TextColumn::make('strength')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dexterity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('intelligence')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('constitution')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('appearance')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('power')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('size')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('education')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('move_rate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('temporary_insanity')
                    ->boolean(),
                Tables\Columns\IconColumn::make('indefinite_insanity')
                    ->boolean(),
                Tables\Columns\IconColumn::make('major_wound')
                    ->boolean(),
                Tables\Columns\IconColumn::make('unconscious')
                    ->boolean(),
                Tables\Columns\IconColumn::make('dying')
                    ->boolean(),
                Tables\Columns\TextColumn::make('hit_points')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sanity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('luck')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('magic_points')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dodge')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('build')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('damage_bonus')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('avatar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('group_id')
                    ->numeric()
                    ->sortable(),
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
            'index'  => Pages\ListCharacters::route('/'),
            'create' => Pages\CreateCharacter::route('/create'),
            'edit'   => Pages\EditCharacter::route('/{record}/edit'),
        ];
    }
}
