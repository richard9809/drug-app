<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReceiptResource\Pages;
use App\Filament\Resources\ReceiptResource\RelationManagers;
use App\Models\Drug;
use App\Models\Receipt;
use DeepCopy\Filter\Filter;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter as FiltersFilter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReceiptResource extends Resource
{
    protected static ?string $model = Receipt::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-clip';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Card::make()
                            ->schema([
                                TextInput::make('receipt_number')
                                    ->default('RPT-'.date('Y-m-d').'-'.rand(1000, 9999))
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                DatePicker::make('receipt_date')
                                    ->default(now())
                                    ->format('Y-m-d')
                                    ->required(),
                                Select::make('hospital_id')
                                    ->label('Hospital')
                                    ->options(
                                        \App\Models\Hospital::query()
                                            ->orderBy('name')
                                            ->pluck('name', 'id')
                                            ->toArray()
                                    )
                                    ->searchable()
                                    ->preload(),
                                Toggle::make('is_approved')
                                    ->label('Is Approved'),
                                Toggle::make('is_verified')
                                    ->label('Is Verified'),
                            ])
                            ->columns(2),

                        Card::make()
                            ->schema([
                                Repeater::make('receiptItems')
                                    ->relationship()
                                    ->schema([
                                        Select::make('drug_id')
                                            ->label('Drug')
                                            ->options(
                                                Drug::query()
                                                    ->orderBy('name')
                                                    ->pluck('name', 'id')
                                                    ->toArray()
                                            )
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                        TextInput::make('quantity')
                                            ->minValue(1)
                                            ->required(),
                                    ])
                                    ->collapsible()
                                    ->createItemButtonLabel('Add Another Drug')
                                    ->columns(2),
                            ])
                    ])
                    ->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                Tables\Columns\TextColumn::make('receipt_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('receipt_date')
                    ->date(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('User')
                    ->options(
                        \App\Models\User::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->toArray()
                    ),
                FiltersFilter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('download')
                    ->label('Download Pdf')
                    ->icon('heroicon-o-download')
                    ->url(fn (Receipt $record) => route('receipts.pdf.download', $record))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListReceipts::route('/'),
            'create' => Pages\CreateReceipt::route('/create'),
            'edit' => Pages\EditReceipt::route('/{record}/edit'),
        ];
    }    
}
