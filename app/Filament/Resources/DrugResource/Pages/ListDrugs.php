<?php

namespace App\Filament\Resources\DrugResource\Pages;

use App\Filament\Resources\DrugResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListDrugs extends ListRecords
{
    protected static string $resource = DrugResource::class;

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->with('category', 'size')
            ->leftJoin('table_warehouse_drugs', 'drugs.id', '=', 'table_warehouse_drugs.drug_id')
            ->select('drugs.*', 'table_warehouse_drugs.quantity as quantity');
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
