<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DispensaryResource\Pages;
use App\Filament\Resources\DispensaryResource\RelationManagers;
use App\Models\Dispensary;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DispensaryResource extends Resource
{
    protected static ?string $model = Dispensary::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Branches';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('address')
                            ->required()
                            ->maxLength(255),
                        Select::make('hospital_id')
                            ->relationship('hospital', 'name')
                            ->preload(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address'),
                Tables\Columns\TextColumn::make('hospital.name')
                    ->label('Hospital')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('hospital_id')
                    ->relationship('hospital', 'name')
                    ->label('Hospital'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListDispensaries::route('/'),
            'create' => Pages\CreateDispensary::route('/create'),
            'edit' => Pages\EditDispensary::route('/{record}/edit'),
        ];
    }    
}
